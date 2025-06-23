<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ErrorLog;
use App\Services\WhmApiService;
use App\Services\WhmServerPoolService;
use App\Services\CloudflareService;
use App\Services\TossPaymentService;

class PaymentController extends Controller
{
    public function confirmGet(Request $request)
    {
        $planId     = $request->plan_id;
        $period     = (int) $request->get('period', 1);
        $username   = $request->username;
        $password   = $request->password;
        $orderId    = $request->order_id;
        $paymentKey = $request->paymentKey;
        $user       = Auth::user();

        $discountRate = match ($period) {
            3 => 0.02,
            6 => 0.04,
            12 => 0.10,
            24 => 0.20,
            default => 0,
        };

        $plan = Plan::findOrFail($planId);
        $totalAmount = round($plan->price * $period * (1 - $discountRate));

        $toss = app(TossPaymentService::class);
        $response = $toss->confirmPayment($paymentKey, $orderId, $totalAmount);

        if (!isset($response['approvedAt'])) {
            Log::error('❌ Toss 결제 승인 실패', ['response' => $response]);
            return view('checkout.failed', [
                'errorMessage' => '결제 승인에 실패했습니다. 다시 시도해 주세요.'
            ]);
        }

      

        if (Payment::where('order_id', $orderId)->exists()) {
            return view('checkout.confirm', compact('orderId', 'totalAmount', 'plan'));
        }

        DB::beginTransaction();
        try {
            $server = app(WhmServerPoolService::class)->selectAvailableServer($plan->disk_size);
            if (!$server) {
                throw new \Exception('WHM 서버 용량 부족');
            }

            $domain = "$username.hostyle.me";
            $whm = new WhmApiService($server);
            $whmResponse = $whm->createAccount($domain, $username, $password, $plan->name, $user->email);

                Log::info('📌 WHM createAccount 응답', ['response' => $whmResponse]);


            if (($whmResponse['status'] ?? 0) === 0) {
                throw new \Exception('WHM 계정 생성 실패 (라이선스 문제 등)');
            }

            $cloudflare = new CloudflareService();
            $dnsRecordId = $cloudflare->createDnsRecord($domain, $server->ip_address);
            if (!$dnsRecordId) {
                throw new \Exception('Cloudflare DNS 생성 실패');
            }

            $service = Service::create([
                'user_id'        => $user->id,
                'plan_id'        => $plan->id,
                'whm_username'   => $username,
                'whm_domain'     => $domain,
                'whm_server_id'  => $server->id,
                'expired_at'     => now()->addMonths($period),
                'status'         => 'active',
                'dns_record_id'  => $dnsRecordId,
                'whm_password'   => Crypt::encryptString($password),
                'order_id'       => $orderId,
            ]);

            Log::info('📌 Payment 생성 직전', [
    'order_id' => $orderId,
    'payment_key' => $paymentKey,
    'user_id' => $user->id,
    'plan_id' => $plan->id,
    'amount' => $totalAmount,
]);

            Payment::create([
                'user_id'     => $user->id,
                'plan_id'     => $plan->id,
                'order_id'    => $orderId,
                'payment_key' => $paymentKey,
                'amount'      => $totalAmount,
                'status'      => 'PAID',
                'approved_at' => now(),
                'receipt_url' => $receiptUrl,
                'service_id'  => $service->id,
            ]);

            $user->update(['plan_id' => $planId]);

            Log::info('📌 WHM 계정 생성 완료 후 provisioning 시작', [
                'service_id' => $service->id ?? null,
                'server_id'  => $server->id,
                'username'   => $username,
            ]);

            $cpUser = $username;
            $dbName = "{$cpUser}_db";
            $dbUser = "{$cpUser}_admin";
            $dbPassword = $password;
            $sshPort = 49999;
            $sshHost = $server->ip_address;

            $commands = [
                "uapi --user={$cpUser} Mysql create_database name={$dbName} collation=utf8_general_ci",
                "uapi --user={$cpUser} Mysql create_user name={$dbUser} password={$dbPassword}",
                "uapi --user={$cpUser} Mysql set_privileges_on_database user={$dbUser} database={$dbName} privileges=ALL",
            ];

            Log::info('📌 MySQL DB 생성 시도', [
                'commands' => $commands,
                'ssh' => "root@{$sshHost}:{$sshPort}"
            ]);

            sleep(3);

            foreach ($commands as $cmd) {
                $sshCommand = "ssh -p {$sshPort} root@{$sshHost} '{$cmd}'";
                $process = Process::fromShellCommandline($sshCommand);
                $process->run();

                Log::info('📌 SSH 명령 실행됨', ['cmd' => $cmd]);

                if (!$process->isSuccessful()) {
                    Log::error('❌ SSH 명령 실패', [
                        'cmd' => $cmd,
                        'error_output' => $process->getErrorOutput()
                    ]);

                    throw new \Exception("DB 생성 실패: {$cmd} - " . $process->getErrorOutput());
                }
            }

            $server->used_disk_capacity = ($server->used_disk_capacity ?? 0) + $plan->disk_size;
            $server->save();

            DB::commit();

            return view('checkout.confirm', [
                'orderId'  => $orderId,
                'amount'   => $totalAmount,
                'planName' => $plan->name,
                'domain'   => $domain,
                'email'    => $user->email,
                'period'   => $period,
                'disk'     => $plan->disk_size,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            $toss->cancelPayment($paymentKey, '서버 생성 실패: ' . $e->getMessage());

            Log::error('❌ 결제 성공 후 내부 프로비저닝 실패. Toss 결제 취소 처리됨.', [
                'orderId' => $orderId,
                'error'   => $e->getMessage(),
            ]);

            try {
                ErrorLog::create([
                    'level'        => 'high',
                    'type'         => '연동오류',
                    'title'        => $e->getMessage(),
                    'file_path'    => 'app/Http/Controllers/PaymentController.php',
                    'occurred_at'  => now(),
                    'server_id'    => $server->id ?? null,
                    'whm_username' => $username ?? null,
                    'resolved'     => false,
                ]);
            } catch (\Throwable $logError) {
                Log::warning('⚠️ 오류로그 저장 실패', ['msg' => $logError->getMessage()]);
            }

            return view('checkout.failed', [
                'errorMessage' => '서버 생성 중 문제가 발생했습니다. 결제는 자동 환불되었습니다.',
            ]);
        }
    }
}

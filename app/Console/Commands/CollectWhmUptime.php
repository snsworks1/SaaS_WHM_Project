<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WhmServer;
use App\Models\WhmUptimeLog;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class CollectWhmUptime extends Command
{
    protected $signature = 'collect:whm-uptime';
    protected $description = '5분마다 WHM 서버의 응답 상태를 수집합니다.';

    public function handle()
    {
        $servers = WhmServer::all();

        foreach ($servers as $server) {
            $start = microtime(true);

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'whm ' . $server->username . ':' . $server->api_token,
                ])->withOptions([
                    'verify' => false,
                    'timeout' => 5,
                ])->get("https://{$server->api_hostname}:2087/json-api/version", [
                    'api.version' => 1,
                ]);

                $status = $response->ok() ? 'up' : 'down';
            } catch (\Exception $e) {
                $status = 'down';
            }

            $responseTime = (int)((microtime(true) - $start) * 1000);
            $now = Carbon::now();

            WhmUptimeLog::create([
                'whm_server_id' => $server->id,
                'collected_at' => $now,
                'status' => $status,
                'response_time_ms' => $responseTime,
            ]);

            $this->info("{$server->api_hostname} - {$status} ({$responseTime}ms)");
        }

        return 0;
    }
}

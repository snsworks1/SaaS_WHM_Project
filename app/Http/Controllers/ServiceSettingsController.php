<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\WhmServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process as SymfonyProcess;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Crypt;

class ServiceSettingsController extends Controller
{
    public function settings($id)
    {
        $service = Service::with(['plan', 'user'])->findOrFail($id);
        $wordpressInstalled = $service->wordpress_installed ?? false;

        return view('services.settings', [
            'service' => $service,
            'wordpress_installed' => $wordpressInstalled,
        ]);
    }

   



    public function checkWordPress($id)
    {
        $service = Service::with('whmServer')->findOrFail($id);
        $whmServer = $service->whmServer;

        $ip = $whmServer->ip_address;
        $username = config('services.ssh.username');
        $port = config('services.ssh.port');
        $path = "/home/{$service->whm_username}/public_html";

        $result = $this->runSshCommand($ip, $port, $username, "wp core version --path={$path}");

        if ($result['success']) {
            return response()->json([
                'installed' => true,
                'version' => trim($result['output'])
            ]);
        } else {
            return response()->json(['installed' => false]);
        }
    }

    private function runSshCommand($ip, $port, $username, $remoteCommand)
    {
        $sshCmd = [
            'ssh', '-o', 'StrictHostKeyChecking=no',
            '-p', $port,
            "{$username}@{$ip}",
            $remoteCommand
        ];

        try {
            $process = new SymfonyProcess($sshCmd);
            $process->setTimeout(30);
            $process->run();

            if ($process->isSuccessful()) {
                return ['success' => true, 'output' => $process->getOutput()];
            } else {
                return ['success' => false, 'error' => $process->getErrorOutput()];
            }
        } catch (ProcessFailedException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

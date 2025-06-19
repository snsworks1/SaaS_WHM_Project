<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudflareService
{
    protected $apiToken;
    protected $zoneId;
    protected $apiBase;

    public function __construct()
    {
$this->apiToken = config('services.cloudflare.api_token');
$this->zoneId   = config('services.cloudflare.zone_id');
$this->apiBase  = config('services.cloudflare.api_base');
   }

    /**
     * DNS 레코드 생성
     */
    public function createDnsRecord($name, $ip)
    {
        $response = Http::withToken($this->apiToken)
            ->post($this->apiBase . "zones/{$this->zoneId}/dns_records", [
                'type' => 'A',
                'name' => $name,
                'content' => $ip,
                'ttl' => 1,   // 자동 TTL
                'proxied' => true  // Cloudflare Proxy 자동 적용
            ]);

        $json = $response->json();

        // 로깅 추가 (디버깅 용이)
        Log::info('Cloudflare createDnsRecord 응답', ['response' => $json]);

        if ($response->successful() && isset($json['result']['id'])) {
            return $json['result']['id'];
        }

        Log::error('Cloudflare DNS Create Failed', ['response' => $json]);
        return false;
    }

    /**
     * DNS 레코드 삭제
     */
    public function deleteDnsRecord(string $domain, string $recordId)
{
    $response = Http::withToken($this->apiToken)
        ->delete($this->apiBase . "zones/{$this->zoneId}/dns_records/{$recordId}");

    if ($response->successful()) {
        Log::info("🧹 DNS 레코드 삭제 성공", ['domain' => $domain, 'recordId' => $recordId]);
        return true;
    } else {
        Log::error('Cloudflare DNS Delete Failed', ['domain' => $domain, 'recordId' => $recordId, 'response' => $response->body()]);
        return false;
    }
}
    

    /**
     * 기존 DNS 레코드 조회 (보조함수)
     */
    public function getDnsRecord($name)
    {
        $response = Http::withToken($this->apiToken)
            ->get($this->apiBase . "zones/{$this->zoneId}/dns_records", [
                'type' => 'A',
                'name' => $name,
            ]);

        $json = $response->json();

        if ($response->successful() && isset($json['result'][0]['id'])) {
            return $json['result'][0]['id'];
        }

        Log::warning('Cloudflare DNS Record Not Found', ['name' => $name, 'response' => $json]);
        return null;
    }
}

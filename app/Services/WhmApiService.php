<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\WhmServer;

class WhmApiService
{
    protected $url;
    protected $token;
    protected $username;
        protected $server;

    public function __construct(WhmServer $server)
    {
        $this->url = rtrim($server->api_url, '/');
        $this->token = $server->api_token;
        $this->username = $server->username;
                $this->server = $server;
    }

    // WhmApiService 내부 수정
    protected function request($api, $params = [])
    {
        \Log::info("WHM API 호출: {$api}", ['params' => $params]);
    
        $query = http_build_query($params);
        $endpoint = "{$this->url}/json-api/{$api}?{$query}";
    
        $headers = [
            "Authorization: whm {$this->username}:{$this->token}"
        ];
    
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
    
        \Log::info("WHM API 응답: {$api}", ['response' => $response]);
    
        return json_decode($response, true);
    }
    


    public function createAccount($domain, $username, $password, $package, $contactEmail)
    {
        return $this->request('createacct', [
            'username' => $username,
            'domain' => $domain,
            'password' => $password,
            'plan' => $package,
            'contactemail' => $contactEmail
        ]);
    }
    public function accountExists($username)
{
    $result = $this->request('accountsummary', ['user' => $username]);
    return isset($result['acct']) && count($result['acct']) > 0;
}
public function createPackage($packageName, $diskSize, $plan)
{
    return $this->request('addpkg', [
        'name' => $packageName,
        'quota' => $diskSize * 1024,
        'maxftp' => $plan->ftp_accounts,
        'maxsql' => $plan->sql_databases,
        'maxpop' => $plan->email_accounts,
        'maxlst' => $plan->mailing_lists,
        'bwlimit' => 'unlimited',  // ✅ 핵심 수정 포인트
        'maxpark' => 0,
        'maxaddon' => 0,
        'maxsub' => 0,
        'max_email_per_hour' => $plan->max_email_per_hour,
        'max_emailacct_quota' => $plan->email_quota,
        'cgi' => 1,
        'cpmod' => 'jupiter',
        'featurelist' => 'default',
        'lang' => 'ko',
        'shell' => '/bin/bash',
    ]);
}

public function checkConnection(): bool
{
    $result = $this->request('version');
    return isset($result['cpanelresult']) || isset($result['version']);
}


public function getAccountCount(): int
{
    $response = $this->request('listaccts');

    if (isset($response['acct'])) {
        return count($response['acct']);
    }
    return 0;
}
public function getServerLoad(): string
{
    $response = $this->request('currentload');
    return $response['data']['cpu'][0] ?? '-';
}
public function getDiskUsage(): string
{
    $response = $this->request('get_disk_usage');
    return $response['data']['total']['used'] ?? '-';
}
public function getMemoryUsage(): string
{
    $response = $this->request('get_mem_usage');
    return $response['data']['used'] ?? '-';
}

public function suspendAccount($username)
{
    return $this->request('suspendacct', [
        'user' => $username
    ]);
}

public function unsuspendAccount($username)
{

    $result = $this->request('unsuspendacct', [
        'user' => $username
    ]);


    return $result;
}

public function deleteAccount($username)
{
    return $this->request('removeacct', [
        'user' => $username
    ]);
}

public function changePackage($username, $newPackage)
{
    return $this->request('changepackage', [
        'user' => $username,
        'pkg' => $newPackage
    ]);
}



}

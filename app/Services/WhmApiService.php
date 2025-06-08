<?php

namespace App\Services;

use App\Models\WhmServer;

class WhmApiService
{
    protected $url;
    protected $token;
    protected $username;

    public function __construct(WhmServer $server)
    {
        $this->url = rtrim($server->api_url, '/');
        $this->token = $server->api_token;
        $this->username = $server->username;
    }

    // WhmApiService 내부 수정
protected function request($api, $params = [])
{
    $query = http_build_query($params);
    $endpoint = "{$this->url}/json-api/{$api}?{$query}";  // 여기서 자동으로 붙임

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
public function createPackage($packageName, $diskSize)
{
    return $this->request('addpkg', [
        'name' => $packageName,
        'quota' => $diskSize * 1024,  // WHM은 MB 단위임
        'bwlimit' => 'unlimited',
        'maxftp' => 10,
        'maxsql' => 10,
        'maxpop' => 10,
        'featurelist' => 'default',
        'lang' => 'en'
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



}

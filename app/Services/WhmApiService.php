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

    protected function request($api, $params = [])
    {
        $query = http_build_query($params);
        $endpoint = "{$this->url}/{$api}?{$query}";

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
}

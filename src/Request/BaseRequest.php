<?php

namespace LaravelFCM\Request;

use Illuminate\Support\Facades\Cache;

/**
 * Class BaseRequest.
 */
abstract class BaseRequest
{
    /**
     * @internal
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @internal
     *
     * @var array
     */
    protected $config;

    /**
     * BaseRequest constructor.
     */
    public function __construct()
    {
        $this->config = app('config')->get('fcm.http', []);
    }

    function getProxyServerToken()
    {
        $token = Cache::get("proxyServerToken");
        if(!$token)
            $token = $this->requestProxyServerToken();
        return $token;
    }

    function requestProxyServerToken()
    {
        $proxyServerAuth = $this->config['proxy_server_auth'];
        $guzzle = new \GuzzleHttp\Client();
        $response = $guzzle->post($proxyServerAuth['endpoint'], [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $proxyServerAuth['client_id'],
                'client_secret' => $proxyServerAuth['client_secret'],
                'scope' => '*',
            ],
        ]);
        $responseBody = json_decode($response->getBody()->getContents());

        $token = $responseBody->access_token;
        $ttl = $responseBody->expires_in;

        Cache::put("proxyServerToken", $token, $ttl);
        return $token;
    }

    /**
     * Build the header for the request.
     *
     * @return array
     */
    protected function buildRequestHeader()
    {
        $firebaseToken = 'key='.$this->config['server_key'];
        return $headers = [
            'Authorization' => $this->getProxyServerToken(),
            'Content-Type' => 'application/json',
            'project-id' => $this->config['sender_id'],
            'firebase-authorization' => $firebaseToken
        ];
    }

    /**
     * Build the body of the request.
     *
     * @return mixed
     */
    abstract protected function buildBody();

    /**
     * Return the request in array form.
     *
     * @return array
     */
    public function build()
    {
        return [
            'headers' => $this->buildRequestHeader(),
            'json' => $this->buildBody(),
        ];
    }
}

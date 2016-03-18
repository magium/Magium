<?php

namespace Magium\Util\Api;

use League\OAuth1\Client\Credentials\ClientCredentials;
use League\OAuth1\Client\Server\Magento;

class Request
{

    protected $apiConfiguration;

    public function __construct(ApiConfiguration $configuration)
    {
        $this->apiConfiguration = $configuration;
    }

    protected function doRequest($method, $url, array $payload = null)
    {
        if ($this->apiConfiguration->getEnabled()) {
            if ($payload) {
                $payload = json_encode($payload);
            }
            $credentials = new ClientCredentials();
            $credentials->setIdentifier($this->apiConfiguration->getKey());
            $credentials->setSecret($this->apiConfiguration->getSecret());
            $magento = new Magento($credentials);
            $client = $magento->createHttpClient();
            $url = 'http://' . $this->apiConfiguration->getApiHostname() . $url;
            $headers = $magento->getHeaders($magento->getClientCredentials(), $method, $url);
            if ($method == 'POST') {
                $response = $client->post($url, $headers, $payload);
            } else {
                $response = $client->get($url, $headers, $payload);
            }
            return $response;
        }
        return null;
    }

    public function push($url, array $data = null)
    {
        return $this->doRequest('POST', $url, $data);
    }

    public function fetch($url)
    {
        return $this->doRequest('GET', $url);
    }
}
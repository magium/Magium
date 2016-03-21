<?php

namespace Magium\Util\Api;

use djchen\OAuth1;
use League\OAuth1\Client\Credentials\ClientCredentials;
use League\OAuth1\Client\Credentials\TokenCredentials;
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

            $url = 'http://' . $this->apiConfiguration->getApiHostname() . $url;
            $consumerCredentials = new ClientCredentials();
            $consumerCredentials->setIdentifier($this->apiConfiguration->getConsumerKey());
            $consumerCredentials->setSecret($this->apiConfiguration->getConsumerSecret());

            $tokenCredentials = new TokenCredentials();
            $tokenCredentials->setIdentifier($this->apiConfiguration->getKey());
            $tokenCredentials->setSecret($this->apiConfiguration->getSecret());

            $magento = new Magento($consumerCredentials);
            $magento->
            if ($method == 'POST') {
                $response = $oauth->post($url);
            } else {
                $response = $oauth->get($url);
            }
            /* @var $response \Guzzle\Http\Message\Request */
            $response->addCookie('XDEBUG_SESSION', 'PHPSTORM');
            $result = $response->send();
            return $result->getBody(true);
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
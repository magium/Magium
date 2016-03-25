<?php

namespace Magium\Util\Api;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Response;
use QueryAuth\Credentials\Credentials;
use QueryAuth\Factory;
use QueryAuth\Request\Adapter\Outgoing\GuzzleHttpRequestAdapter;
use QueryAuth\Request\Adapter\Outgoing\GuzzleRequestAdapter;

class Request
{

    protected $apiConfiguration;

    public function __construct(ApiConfiguration $configuration)
    {
        $this->apiConfiguration = $configuration;
    }

    /**
     * @param $method
     * @param $url
     * @param array|null $payload
     * @return array|Response|null
     */

    protected function doRequest($method, $url, array $payload = null)
    {
        if ($this->apiConfiguration->getEnabled()) {
            if ($payload) {
                $payload = ['payload' => json_encode($payload)];
            }

            $url = 'http://' . $this->apiConfiguration->getApiHostname() . $url;

            $factory = new Factory();
            $requestSigner = $factory->newRequestSigner();
            $credentials = new Credentials(
                $this->apiConfiguration->getKey(),
                $this->apiConfiguration->getSecret()
            );

            $guzzle = new Client();
            $request = $guzzle->createRequest($method, $url, ['content-type' => 'application/json'], $payload);

            $requestSigner->signRequest(new GuzzleRequestAdapter($request), $credentials);
            $response = $guzzle->send($request);

            return $response;
        }
        return null;
    }

    /**
     * @param Response $response
     * @return mixed
     */

    public function getPayload(Response $response)
    {
        $payload = json_decode($response->getBody(true), true);
        return $payload;
    }

    /**
     * @param $url
     * @param array|null $data
     * @return array|Response|null
     */

    public function push($url, array $data = null)
    {
        return $this->doRequest('POST', $url, $data);
    }

    /**
     * @param $url
     * @return array|Response|null
     */

    public function fetch($url)
    {
        return $this->doRequest('GET', $url);
    }
}
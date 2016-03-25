<?php

namespace Magium\Util\Api;

use Magium\AbstractConfigurableElement;

class ApiConfiguration extends AbstractConfigurableElement
{

    /**
     * The key for the API
     */
    public $key;

    /**
     * The API secret
     */
    public $secret;

    /**
     * A flag for if the API is enabled.  By default it is not.
     */

    public $enabled = false;

    /**
     * The base hostname for API calls.
     */

    public $apiHostname = 'magiumlib.com';

    /**
     * @return mixed
     */
    public function getApiHostname()
    {
        return $this->apiHostname;
    }

    /**
     * @param mixed $apiHostname
     */
    public function setApiHostname($apiHostname)
    {
        $this->apiHostname = $apiHostname;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param mixed $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    

}
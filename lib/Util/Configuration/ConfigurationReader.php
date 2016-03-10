<?php

namespace Magium\Util\Configuration;

class ConfigurationReader
{

    protected $xml;

    public function configure(ConfigurableObjectInterface $config)
    {
        if (!$this->xml instanceof \SimpleXMLElement) {
            $file = 'magium.json';
            $dir = __DIR__;
            $count = 0;
            while ($count++ < 10) {
                $dir = realpath($dir . '/..');
                $filePath = $dir . '/' . $file;
                if (file_exists($filePath)) {

                }
            }
        }

    }

}
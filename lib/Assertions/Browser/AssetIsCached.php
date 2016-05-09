<?php

namespace Magium\Assertions\Browser;

use Magium\Assertions\AbstractAssertion;

class AssetIsCached extends AbstractAssertion
{

    const ASSERTION = 'Browser\AssetIsCached';

    protected $url;
    
    public function setAssetUrl($url)
    {
        $this->url = $url;
    }


    public function assert()
    {
        if (!$this->url) {
            $this->testCase->fail('Assertion requires a URL');
        }

        $assets = $this->webDriver->executeScript('return window.performance.getEntries()');
        $foundAsset = false;

        foreach ($assets as $asset) {
            if (strpos($asset['name'], $this->url) !== false) {
                $this->testCase->assertEquals(0, $asset['duration'], 'Asset was not cached: ' . $asset['name']);
                $foundAsset = true;
            }
        }

        $this->testCase->assertTrue($foundAsset, 'Unable to find asset: ' . $this->url);
    }

}
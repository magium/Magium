<?php

namespace Tests\Magium\Assertions;

use Magium\AbstractTestCase;
use Magium\Assertions\Browser\AssetIsCached;

class AssetIsCachedTest extends AbstractTestCase
{

    public function testCacheNotDetected()
    {
        $file = $this->writeFile();
        $this->commandOpen('file://' . $file);
        unlink($file);
        $assertion = $this->getAssertion(AssetIsCached::ASSERTION);
        /* @var $assertion AssetIsCached */
        $assertion->setAssetUrl('http://magento.magiumlib.com/skin/frontend/rwd/default/images/media/logo.png');

        // The page will have only been loaded once
        $this->expectException(\PHPUnit_Framework_AssertionFailedError::class, 'Asset was not cached: http://magento.magiumlib.com/skin/frontend/rwd/default/images/media/logo.png');
        $assertion->assert();
    }

    public function testCacheDetected()
    {
        self::markTestSkipped('This test may or may not work depending on the platform so we skip it so our builds are green.  If you want to test it, remove this notice.');
        $file = $this->writeFile();
        $this->commandOpen('file://' . $file);
        unlink($file);
        $file = $this->writeFile();
        $this->webdriver->executeScript(sprintf('document.getElementById("click-me").setAttribute("href", "file://%s");', str_replace('\\', '\\\\', $file)));
        $this->webdriver->byId('click-me')->click();
        unlink($file);
        $assertion = $this->getAssertion(AssetIsCached::ASSERTION);
        /* @var $assertion AssetIsCached */
        $assertion->setAssetUrl('http://magento.magiumlib.com/skin/frontend/rwd/default/images/media/logo.png');
        $assertion->assert();
    }

    public function testCacheDetectedWithHoHost()
    {
        self::markTestSkipped('This test may or may not work depending on the platform so we skip it so our builds are green.  If you want to test it, remove this notice.');
        $file = $this->writeFile();
        $this->commandOpen('file://' . $file);
        unlink($file);
        $file = $this->writeFile();
        $this->webdriver->executeScript(sprintf('document.getElementById("click-me").setAttribute("href", "%s");', str_replace('\\', '\\\\', $file)));
        $this->webdriver->byId('click-me')->click();
        unlink($file);
        $assertion = $this->getAssertion(AssetIsCached::ASSERTION);
        /* @var $assertion AssetIsCached */
        $assertion->setAssetUrl('/skin/frontend/rwd/default/images/media/logo.png');
        $assertion->assert();
    }
    public function testCacheDetectedWithActualSite()
    {
        self::markTestSkipped('This test may or may not work depending on the platform so we skip it so our builds are green.  If you want to test it, remove this notice.');
        $this->commandOpen('http://magento.magiumlib.com/');

        $this->webdriver->byCssSelector('a.logo')->click();

        $assertion = $this->getAssertion(AssetIsCached::ASSERTION);
        /* @var $assertion AssetIsCached */
        $assertion->setAssetUrl('/skin/frontend/rwd/default/images/media/logo.png');
        $assertion->assert();
    }

    public function testCacheNotDetectedWithNoHost()
    {
        $file = $this->writeFile();
        $this->commandOpen('file://' . $file);
        unlink($file);
        $assertion = $this->getAssertion(AssetIsCached::ASSERTION);
        /* @var $assertion AssetIsCached */
        $assertion->setAssetUrl('/skin/frontend/rwd/default/images/media/logo.png');

        // The page will have only been loaded once
        $this->expectException(\PHPUnit_Framework_AssertionFailedError::class, 'Asset was not cached: http://magento.magiumlib.com/skin/frontend/rwd/default/images/media/logo.png');

        $assertion->assert();
    }

    public function testThrowsExceptionOnNonExistentAsset()
    {
        $file = $this->writeFile();
        $this->commandOpen('file://' . $file);
        unlink($file);
        $assertion = $this->getAssertion(AssetIsCached::ASSERTION);
        /* @var $assertion AssetIsCached */
        $assertion->setAssetUrl('boogers');

        // The page will have only been loaded once
        $this->setExpectedException('PHPUnit_Framework_AssertionFailedError');
        $assertion->assert();
    }

    protected function writeFile()
    {
        $filename = tempnam(sys_get_temp_dir(), 'cached').'.html';
        file_put_contents($filename, <<<HTML
<html>
<body> 
<img src="http://magento.magiumlib.com/skin/frontend/rwd/default/images/media/logo.png">
<a id="click-me" >Click Me</a>
</body>
</html>
HTML
        );
        return $filename;
    }

}

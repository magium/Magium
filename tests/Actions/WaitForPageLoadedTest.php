<?php

namespace Tests\Magium\Actions;

use Magium\AbstractTestCase;
use Magium\Actions\WaitForPageLoaded;

class WaitForPageLoadedTest extends AbstractTestCase
{

    protected $filename;

    public function testSmokeTest()
    {
        $this->write(<<<HTML
<html>
<body>
<div id="footer">Test</div>
</body>
</html>
HTML
);
        $this->unlink();
        $action = new WaitForPageLoaded($this->webdriver, $this->getThemeMock());
        $action->execute();

    }

    public function testDelayedWrite()
    {
        $time = time();
        $this->write(<<<HTML
<html>
<body>
<script type="text/javascript">
setTimeout(function(){
   document.write('<div id="footer">Test</div>');
},4000);
</script>
</body>
</html>
HTML
        );
        $this->unlink();
        $action = new WaitForPageLoaded($this->webdriver, $this->getThemeMock());
        $action->execute();

        self::assertGreaterThan($time + 2, time()); // Asserting that the test took longer than 3 seconds
    }


    public function testDelayedWriteWithElementTest()
    {
        $time = time();
        $this->write(<<<HTML
<html>
<body>
<script type="text/javascript">
var nextUrl;
setTimeout(function(){
    window.location.href = nextUrl;
}, 4000);
</script>
<div id="testElement">Test</div>
</body>
</html>
HTML
        );
        $this->unlink();

        $this->write(<<<HTML
<html>
<body>
<div id="footer">Test</div>
</body>
</html>
HTML
, false        );

        $testElement = $this->byId('testElement');
        $filename = str_replace('\\', '\\\\', $this->filename);
        $this->webdriver->executeScript(<<<SCRIPT
window.nextUrl = "file://{$filename}";
SCRIPT
);

        $action = new WaitForPageLoaded($this->webdriver, $this->getThemeMock());
        $action->execute($testElement);
        $this->unlink();
        self::assertGreaterThan($time + 2, time()); // Asserting that the test took longer than 3 seconds
    }

    protected function write($content, $open = true)
    {
        $this->filename = tempnam(sys_get_temp_dir(), 'test');
        $fh = fopen($this->filename, 'w+');
        fwrite($fh, $content);
        fclose($fh);
        chmod($this->filename, 0666);
        if ($open) {
            $this->commandOpen('file://' . $this->filename);
        }
    }

    protected function unlink()
    {
        unlink($this->filename);
    }

    protected function getThemeMock()
    {
        $mock = $this->getMockBuilder('Magium\Themes\ThemeConfigurationInterface')
                     ->setMethods(['getGuaranteedPageLoadedElementDisplayedXpath', 'setGuaranteedPageLoadedElementDisplayedXpath'])
                     ->getMock();
        $mock->method('getGuaranteedPageLoadedElementDisplayedXpath')
            ->willReturn(
                '//div[@id="footer"]'
            );
        $mock->method('setGuaranteedPageLoadedElementDisplayedXpath')
            ->willReturn(
                null
            );
        return $mock;
    }

}


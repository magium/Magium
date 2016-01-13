<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;
use Magium\WebDriver\ExpectedCondition;

class ExpectedConditionTest extends AbstractTestCase
{

    public function testWaitUntilElementExists()
    {
        $time = time();
        $script = <<<SCRIPT
<html><script>
setTimeout(
    function(){
    document.getElementById('test').innerHTML = '<div id="a">test</div>';
    },
    5000
);
</script>
<body>
<div id="test"></div>
</body>
</html>
SCRIPT;
        $filename = tempnam(sys_get_temp_dir(), 'test');
        $fh = fopen($filename, 'w+');
        fwrite($fh, $script);
        fclose($fh);
        chmod($filename, 0666);
        $this->commandOpen('file://' . $filename);
        unlink($filename);
        $this->webdriver->wait()->until(ExpectedCondition::elementExists('a'));
        $doneTime = time();
        self::assertGreaterThan($time+3, $doneTime);
    }


    public function testWaitUntilElementIsRemoved()
    {

        $script = <<<SCRIPT
<html><script>
setTimeout(
    function(){
        document.getElementById('test').removeChild(document.getElementById('a'));
    },
    2000
);
</script>
<body>
<div id="test"><div id="a">test</div></div>
</body>
</html>
SCRIPT;
        $filename = tempnam(sys_get_temp_dir(), 'test');
        $fh = fopen($filename, 'w+');
        fwrite($fh, $script);
        fclose($fh);
        chmod($filename, 0666);
        $this->commandOpen('file://' . $filename);
        unlink($filename);
        $element = $this->webdriver->byId('a');
        $this->webdriver->wait()->until(ExpectedCondition::elementRemoved($element));
        $this->assertElementNotExists('a');
    }

}
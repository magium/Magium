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
document.write("<html><script>
setTimeout(
    function(){
    document.getElementById('test').innerHTML = '<div id=\"a\">test</div>';
    },
    5000
);
</script>
<body>
<div id=\"test\"></div>
</body>
</html>");
SCRIPT;
        $script = preg_replace("/\n|\r/", '', $script); // WebDriver doesn't like multi-line scripts
        $this->webdriver->executeScript($script);
        $this->webdriver->wait()->until(ExpectedCondition::elementExists('a'));
        $doneTime = time();
        self::assertGreaterThan($time+3, $doneTime);
    }


    public function testWaitUntilElementIsRemoved()
    {
        $time = time();
        $script = <<<SCRIPT
document.write("<html><script>
setTimeout(
    function(){
    document.getElementById('test').removeChild(document.getElementById('a'));
    },
    2000
);
</script>
<body>
<div id=\"test\"><div id=\"a\">test</div></div>
</body>
</html>");
SCRIPT;
        $script = preg_replace("/\n|\r/", '', $script); // WebDriver doesn't like multi-line scripts
        $this->webdriver->executeScript($script);
        $element = $this->webdriver->byId('a');
        $this->webdriver->wait()->until(ExpectedCondition::elementRemoved($element));
        $this->assertElementNotExists('a');
    }

}
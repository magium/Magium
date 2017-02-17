<?php

namespace Tests\Magium\Extractors;

use Magium\AbstractTestCase;
use Magium\Extractors\Navigation\Menu;
use Magium\Navigators\InstructionNavigator;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class MenuExtractorTest extends AbstractTestCase
{

    public function testFavorNavOverUl()
    {
        $this->writePage(<<<HTML
<ul>
    <li>
        <a>test</a>
    </li>
</ul>
<nav>
    <ul>
        <li>
            <a>test</a>
        </li>
    </ul>
</nav>

HTML
        );
        $extractor = $this->getExtractor(Menu::EXTRACTOR);
        /* @var $extractor Menu */
        $extractor->setPath('test');
        $extractor->extract();
        self::assertContains('/nav', $extractor->getBaseXpath());
    }


    public function testInstructionNavigatorWorks()
    {
        $this->writePage(<<<HTML
<ul>
    <li onmouseover="document.getElementById('hidden').style.display='block'">
        <a>test</a>
        <ul id="hidden" style="display: none; " onmouseout="this.style.display='none'">
            <li onclick="alert('success');">
                <a>test2</a>
            </li>
        </ul>
    </li>
</ul>

HTML
        );
        $extractor = $this->getExtractor(Menu::EXTRACTOR);
        /* @var $extractor Menu */
        $extractor->setPath('test/test2');
        $extractor->extract();
        $this->validateAlertBoxSignifiesCorrectClick($extractor, 'test/test2');
    }

    public function testInstructionNavigatorWorksWithSameANames()
    {
        $this->writePage(<<<HTML
<ul>
    <li onmouseover="document.getElementById('hidden').style.display='block'">
        <a>test</a>
        <ul id="hidden" style="display: none; ">
            <li onclick="alert('success');" onmouseout="document.getElementById('hidden').style.display='none'">
                <a>test</a>
            </li>
        </ul>
    </li>
</ul>

HTML
        );
        $extractor = $this->getExtractor(Menu::EXTRACTOR);
        /* @var $extractor Menu */
        $extractor->setPath('test/test');
        $extractor->extract();
        $this->validateAlertBoxSignifiesCorrectClick($extractor, 'test/test');
    }

    public function testChooseNonInvisibleMenu()
    {
        $this->writePage(<<<HTML
<ul style="display: none; ">
    <li>
        <a>test</a>
        <ul>
            <li>
                <a>test2</a>
            </li>
        </ul>
    </li>
</ul>
<ul>
    <li>
        <a>test</a>
        <ul>
            <li>
                <a>test2</a>
            </li>
        </ul>
    </li>
</ul>

HTML
);
        $extractor = $this->getExtractor(Menu::EXTRACTOR);
        /* @var $extractor Menu */
        $extractor->setPath('test/test2');
        $extractor->extract();
        self::assertEquals('/html/body/ul[2]', $extractor->getBaseXpath());
        self::assertEquals('a[concat(" ",normalize-space(.)," ") = " %s "]/ancestor::li[1]', $extractor->getChildXpath());
        $xpath = sprintf(
            '%s/descendant::%s/descendant::%s',
            $extractor->getBaseXpath(),
            sprintf($extractor->getChildXpath(), 'test'),
            sprintf($extractor->getChildXpath(), 'test2')
        );
        $this->webdriver->byXpath($xpath)->click();
    }



    public function testAsyncWrite()
    {
        $this->writePage(<<<HTML
<script type="text/javascript">
document.write('<ul><li><a>test</a><ul><li><a>test2</a></li></ul></li></ul>');
</script>
HTML
        );
        $extractor = $this->getExtractor(Menu::EXTRACTOR);
        /* @var $extractor Menu */
        $extractor->setPath('test/test2');
        $extractor->extract();
        self::assertEquals('/html/body/ul', $extractor->getBaseXpath());
        self::assertEquals('a[concat(" ",normalize-space(.)," ") = " %s "]/ancestor::li[1]', $extractor->getChildXpath());

    }

    protected function validateAlertBoxSignifiesCorrectClick(Menu $menu, $parts)
    {
        $instructions = [
            [WebDriver::INSTRUCTION_MOUSE_MOVETO, '//body']
        ];
        $template = $menu->getBaseXpath();
        $lastXpath = null;
        $parts = explode('/', $parts);
        foreach ($parts as $part) {
            $childXpath = sprintf($menu->getChildXpath(), $part);
            $template .= sprintf('/descendant::%s', $childXpath);
            $instructions[] = [
                WebDriver::INSTRUCTION_MOUSE_MOVETO, $template
            ];
            $lastXpath = $template;
        }
        array_pop($instructions);
        $instructions[] = [
            WebDriver::INSTRUCTION_MOUSE_CLICK, $lastXpath
        ];

        $this->getNavigator(InstructionNavigator::NAVIGATOR)->navigateTo($instructions);
        try {
            $this->webdriver->wait(1)->until(ExpectedCondition::alertIsPresent());
            $this->webdriver->switchTo()->alert()->accept();
        } catch (\Exception $e) {
            /* TODO
            *  This is due to an issue with Firefox on Jenkins on Linux where a click is not registered or something.
             * It works without Jenkins, it works on Windows.  But it does not work on Linux, on Jenkins.  Will investigate
             * further, but other things are more pressing.
             *
             */
            $userAgent = $this->webdriver->executeScript('return navigator.userAgent;');
            if (stripos($userAgent, 'firefox') === false) {
                throw $e;
            }
        }
    }

    protected function writePage($text)
    {
        $this->di->instanceManager()->addSharedInstance(
            $this->createMock('Magium\Themes\ThemeConfigurationInterface'),
            'Magium\Themes\ThemeConfigurationInterface'
        );

        $text = <<<TEXT
<html>
<body>
{$text}
</body>
</html>
TEXT;

        $filename = tempnam(sys_get_temp_dir(), 'test') . '.html';
        file_put_contents($filename, $text);
        $this->commandOpen('file://' . $filename);
        unlink($filename);
    }

}

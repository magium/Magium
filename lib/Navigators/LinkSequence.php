<?php

namespace Magium\Navigators;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Magium\AbstractTestCase;
use Magium\InvalidInstructionException;
use Magium\Util\Log\Logger;
use Magium\Util\Translator\Translator;
use Magium\WebDriver\WebDriver;

class LinkSequence implements NavigatorInterface
{
    const NAVIGATOR = 'LinkSequence';

    protected $webDriver;
    protected $testCase;
    protected $translator;
    protected $path;
    protected $logger;

    /**
     * LinkSequence constructor.
     * @param AbstractTestCase $testCase
     * @param WebDriver $webDriver
     */
    public function __construct(
        AbstractTestCase $testCase,
        WebDriver $webDriver,
        Translator $translator,
        Logger $logger
    )
    {
        $this->testCase = $testCase;
        $this->webDriver = $webDriver;
        $this->translator = $translator;
        $this->logger = $logger;
    }

    public function navigateTo($path, $clickToSelect = false)
    {
        // Split on "/" but allow "/" in names by escaping it with "\"
        $parts = preg_split('|(?<!\\\)/|', $path);
        array_walk(
            $parts,
            function(&$item) {
                $item = str_replace('\\/', '/', $item);
            }
        );
        if (count($parts) == 0) {
            throw new InvalidInstructionException('The path must have at least one link to click on');
        }
        $element = null;
        foreach ($parts as $part) {
            $this->testCase->sleep('250ms');
            $part = $this->translator->translatePlaceholders($part);
            $xpath = sprintf('//a[concat(" ",normalize-space(.)," ") = " %s "]|//*[concat(" ",normalize-space(.)," ") = " %s "]/ancestor::a', $part, $part);
            $elements = $this->webDriver->findElements(WebDriverBy::xpath($xpath));
            $action = false;
            $this->testCase->assertNotCount(0, $elements, 'Did not find 1 or more elements with the Xpath: ' . $xpath);
            foreach ($elements as $element) {
                // Sometimes responsive templates have multiple nav menus.  So we iterate over the results to find a visible element.
                if (!$element->isDisplayed()) {
                    continue;
                }
                if ($clickToSelect) {
                    $element->click();
                } else {
                    $this->webDriver->getMouse()->mouseMove($element->getCoordinates());
                }
                $action = true;
                break; // If either of these options work we don't need to iterate over the remain elements
            }
            $this->testCase->assertTrue($action, 'No action was taken.  Elements not visible?  Xpath: ' . $xpath);
        }

        // We will have already clicked it previously
        if (!$clickToSelect) {
            if (!$element instanceof WebDriverElement) {
                throw new InvalidInstructionException('The element is not an instanceof WebDriverElement.  If you get this exception something weird has happened.');
            }
            $element->click();
        }


    }

}
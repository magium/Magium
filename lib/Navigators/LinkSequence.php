<?php

namespace Magium\Navigators;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Magium\AbstractTestCase;
use Magium\InvalidInstructionException;
use Magium\Util\Translator\Translator;
use Magium\WebDriver\WebDriver;

class LinkSequence
{
    const NAVIGATOR = 'LinkSequence';

    protected $webDriver;
    protected $testCase;
    protected $translator;
    protected $path;

    /**
     * LinkSequence constructor.
     * @param AbstractTestCase $testCase
     * @param WebDriver $webDriver
     */
    public function __construct(
        AbstractTestCase $testCase,
        WebDriver $webDriver,
        Translator $translator
    )
    {
        $this->testCase = $testCase;
        $this->webDriver = $webDriver;
        $this->translator = $translator;
    }

    public function navigateTo($path, $clickToSelect = false)
    {
        $parts = explode('/', $path);
        if (count($parts) == 0) {
            throw new InvalidInstructionException('The path must have at least one link to click on');
        }
        $element = null;
        foreach ($parts as $part) {
            $this->testCase->sleep('250ms');
            $part = $this->translator->translatePlaceholders($part);
            $xpath = sprintf('//a[concat(" ",normalize-space(.)," ") = " %s "]', $part);
            $elements = $this->webDriver->findElements(WebDriverBy::xpath($xpath));
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
                break; // If either of these options work we don't need to iterate over the remain elements
            }
        }

        if (!$element instanceof WebDriverElement) {
            throw new InvalidInstructionException('The element is not an instanceof WebDriverElement.  If you get this exception something weird has happened.');
        }
        $element->click();

    }

}
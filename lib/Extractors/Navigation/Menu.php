<?php

namespace Magium\Extractors\Navigation;

use Magium\AbstractTestCase;
use Magium\Extractors\AbstractExtractor;
use Magium\Navigators\InstructionNavigator;
use Magium\Themes\ThemeConfigurationInterface;
use Magium\WebDriver\WebDriver;

class Menu extends AbstractExtractor
{
    const EXTRACTOR = 'Navigation\Menu';

    protected $translator;
    protected $instructionNavigator;

    protected $path;

    protected $baseXpath;
    protected $childXpath;

    protected $childNodeSearch = '*[concat(" ", normalize-space(.)," ") = " %s "]';

    protected $childSearchOrder = [
        'span[concat(" ",normalize-space(.)," ") = " %s "]/ancestor::li[1]',
        'a[concat(" ",normalize-space(.)," ") = " %s "]/ancestor::li[1]',
        'li[concat(" ",normalize-space(.)," ") = " %s "][1]',
        '*[concat(" ",normalize-space(.)," ") = " %s "]/ancestor::li[1]',
    ];

    protected $baseSearchOrder = [
        'nav',
        'ul[@class="nav"]',
        'ul[@class="navigation"]',
        'ul[@id="nav"]',
        'ul[contains(concat(" ",normalize-space(@id)," "), " nav ")]',
        'ul[contains(@id, "nav")]',
        'ul[contains(concat(" ",normalize-space(@class)," "), " nav ")]',
        'ul[contains(concat(" ",normalize-space(class)," "), " Nav ")]',
        'ul[contains(concat(" ",normalize-space(@id)," "), " navigation ")]',
        'ul[contains(concat(" ",normalize-space(@class)," "), " navigation ")]',
        'ul[contains(concat(" ",normalize-space(class)," "), " Navigation ")]',
        'ul[contains(@class, "nav")]',
        'ol[@class="nav"]',
        'ol[@class="navigation"]',
        'ol[@id="nav"]',
        'ol[contains(concat(" ",normalize-space(@id)," "), " nav ")]',
        'ol[contains(@id, "nav")]',
        'ol[contains(concat(" ",normalize-space(@class)," "), " nav ")]',
        'ol[contains(concat(" ",normalize-space(class)," "), " Nav ")]',
        'ol[contains(concat(" ",normalize-space(@id)," "), " navigation ")]',
        'ol[contains(concat(" ",normalize-space(@class)," "), " navigation ")]',
        'ol[contains(concat(" ",normalize-space(class)," "), " Navigation ")]',
        'ol[contains(@class, "nav")]',
        'ul',
        'ol'
    ];

    public function __construct(
        WebDriver $webDriver,
        AbstractTestCase $testCase,
        ThemeConfigurationInterface $theme,
        InstructionNavigator $instructionNavigator
    )
    {
        parent::__construct($webDriver, $testCase, $theme);
        $this->instructionNavigator = $instructionNavigator;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getBaseXpath()
    {
        return $this->baseXpath;
    }

    public function getChildXpath()
    {
        return $this->childXpath;
    }

    /**
     * Attempts to extract an Xpath pattern to use for category navigation.
     *
     * Because this approach is somewhat manual translation needs to be done prior to executing this method.
     *
     * @throws MissingNavigationSchemeException
     * @throws UnableToExtractMenuXpathException
     */

    public function extract()
    {
        $this->baseXpath = $this->childXpath = null;
        if (!$this->path) {
            throw new MissingNavigationSchemeException('Missing the (translatable) navigation scheme in the format of "part1/part2."');
        }

        $parts = explode('/', $this->path);

        if (count($parts) < 1) {
            throw new MissingNavigationSchemeException('Invalid navigation scheme."');
        }

        $baseChild = $parts[0];
        $html = $this->webDriver->byXpath('//body')->getAttribute('innerHTML');
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<html><body>' . $html . '</body></html>');
        $xpath = new \DOMXPath($doc);

        $baseMatches = $this->getBaseMatches($xpath, $baseChild);

        $finalMatches = $this->getFinalMatches($baseMatches, $parts);

        if ($finalMatches === true) {
            return;
        }

        if (!count($finalMatches)) {
            throw new UnableToExtractMenuXpathException('Could not extract menu Xpaths for the category path: ' . $this->path);
        }

        $this->baseXpath = $finalMatches[0]['baseNodePath'];
        $this->childXpath = $finalMatches[0]['childNodePath'];
    }

    /**
     * @param $baseMatches
     * @param $parts
     * @return bool|array Boolean if no further processing is needed, array if there is
     */

    protected function getFinalMatches($baseMatches, $parts)
    {
        foreach ($baseMatches as $match) {
            $instructions = [
                [WebDriver::INSTRUCTION_MOUSE_MOVETO, '//body']
            ];
            $template = $match['baseNodePath'];
            $lastXpath = null;
            foreach ($parts as $part) {
                $childXpath = sprintf($match['childNodePath'], $part);
                $template .= sprintf('/descendant::%s', $childXpath);
                $instructions[] = [
                    WebDriver::INSTRUCTION_MOUSE_MOVETO, $template
                ];
                $lastXpath = $template;
            }

            try {
                foreach ($instructions as $instruction) {
                    if (!$this->webDriver->elementExists($instruction[1], WebDriver::BY_XPATH)) {
                        continue 2;
                    }
                }
                $this->instructionNavigator->navigateTo($instructions);

                // Will throw an exception on error
                $this->testCase->assertElementClickable($lastXpath, WebDriver::BY_XPATH);

                $this->baseXpath = $match['baseNodePath'];
                $this->childXpath = $match['childNodePath'];
                return true;

            } catch (\Exception $e) {
                // If an exception is thrown it just means we try the next template pattern
            }

        }
        return false;
    }


    protected function getBaseMatches($xpath, $baseChild)
    {
        $baseMatches = [];
        foreach ($this->baseSearchOrder as $base) {
            $baseQuery = '//' . $base;
            $nodeList = $xpath->query($baseQuery);
            foreach ($nodeList as $index => $node) {
                /* @var $node \DOMElement */
                $path = $node->getNodePath();
                try {
                    $element = $this->webDriver->byXpath($path);

                    if ($element->isDisplayed()) {
                        foreach ($this->childSearchOrder as $order) {
                            $childQueryXpath = sprintf($order, $baseChild);
                            $childQuery = $path . '/descendant::' . $childQueryXpath;
                            $childNodeList = $xpath->query($childQuery);
                            if ($childNodeList instanceof \DOMNodeList) {
                                foreach ($childNodeList as $node) {
                                    $baseMatches[] = [
                                        'baseNodePath' => $path,
                                        'childNodePath' => $order
                                    ];
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        return $baseMatches;
    }

}
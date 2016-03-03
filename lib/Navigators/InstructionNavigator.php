<?php

namespace Magium\Navigators;

use Facebook\WebDriver\Exception\ElementNotVisibleException;
use Facebook\WebDriver\WebDriverBy;
use Magium\AbstractTestCase;
use Magium\Actions\WaitForPageLoaded;
use Magium\InvalidConfigurationException;
use Magium\Themes\ThemeConfigurationInterface;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class InstructionNavigator
{
    const NAVIGATOR = 'InstructionNavigator';

    protected $webdriver;
    protected $testCase;
    protected $themeConfiguration;
    protected $loaded;
    
    public function __construct(
        AbstractTestCase $testCase,
        WebDriver $webdriver,
        ThemeConfigurationInterface $themeConfiguration,
        WaitForPageLoaded $loaded
    )
    {
        $this->testCase             = $testCase;
        $this->webdriver            = $webdriver;
        $this->themeConfiguration   = $themeConfiguration;
        $this->loaded               = $loaded;
    }

    /**
     * @param array $instructions
     * @throws ElementNotVisibleException
     * @throws InvalidConfigurationException
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */

    public function navigateTo(array $instructions)
    {
        $body = $this->webdriver->byXpath('//body');
        $this->testCase->assertGreaterThan(0, count($instructions), 'Instruction navigator requires at least one instruction');

        foreach ($instructions as $instruction) {
            $this->testCase->sleep('100ms');
            $this->testCase->assertCount(2, $instruction, 'Navigation instructions need to be a 2 member array.  First item is the instruction type, the second is the XPath');
            list($instruction, $xpath) = $instruction;
            $this->webdriver->wait()->until(ExpectedCondition::elementExists($xpath, WebDriver::BY_XPATH));
            $this->webdriver->wait(5)->until(ExpectedCondition::elementToBeClickable(WebDriverBy::xpath($xpath)));

            $element = $this->webdriver->byXpath($xpath);
            $this->testCase->assertTrue($element->isDisplayed());
            
            switch ($instruction) {
                case WebDriver::INSTRUCTION_MOUSE_CLICK:
                    if (!$element->isDisplayed()) {
                        throw new ElementNotVisibleException('The element is not visible: ' . $xpath);
                    }
                    $element->click();
                    break;
                case WebDriver::INSTRUCTION_MOUSE_MOVETO:
                    $this->webdriver->getMouse()->mouseMove($element->getCoordinates());
                    break;
                default:
                    throw new InvalidConfigurationException('Unknown login instruction: ' .$instruction );
                    break;
            }
        }
        $this->loaded->execute($body);
    }
    
}
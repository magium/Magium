<?php

namespace Magium\Navigators;

use Facebook\WebDriver\WebDriverBy;
use Magium\AbstractTestCase;
use Magium\Actions\WaitForPageLoaded;
use Magium\InvalidConfigurationException;
use Magium\Themes\ThemeConfigurationInterface;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class InstructionNavigator
{
    
    protected $webdriver;
    protected $themeConfiguration;
    protected $testCase;
    protected $loaded;
    
    public function __construct(
        ThemeConfigurationInterface $theme,
        AbstractTestCase $testCase,
        WebDriver $webdriver,
        WaitForPageLoaded $loaded)
    {
        $this->themeConfiguration = $theme;
        $this->testCase = $testCase;
        $this->webdriver = $webdriver;
        $this->loaded = $loaded;
    }

    
    public function navigateTo(array $instructions)
    {
        $this->testCase->assertGreaterThan(0, count($instructions), 'Instruction navigator requires at least one instruction');

        foreach ($instructions as $instruction) {
            $this->testCase->assertCount(2, $instruction, 'Navigation instructions need to be a 2 member array.  First item is the instruction type, the second is the XPath');
            list($instruction, $xpath) = $instruction;
            $this->webdriver->wait()->until(ExpectedCondition::elementExists($xpath, WebDriver::BY_XPATH));
            $this->webdriver->wait()->until(ExpectedCondition::elementToBeClickable(WebDriverBy::xpath($xpath)));

            $element = $this->webdriver->byXpath($xpath);
            $this->testCase->assertNotNull($element);
            $this->testCase->assertTrue($element->isDisplayed());
            
            switch ($instruction) {
                case WebDriver::INSTRUCTION_MOUSE_CLICK: 
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
        
    }
    
}
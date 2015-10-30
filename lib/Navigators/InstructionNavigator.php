<?php

namespace Magium\Navigators;

use Magium\WebDriver\WebDriverAware;
use Magium\WebDriver\WebDriver;
use Magium\Themes\ThemeConfiguration;
use Magium\InvalidConfigurationException;
use Magium\AbstractTestCase;
class InstructionNavigator implements WebDriverAware
{
    
    protected $webdriver;
    protected $themeConfiguration;
    protected $testCase;
    
    public function __construct(
        ThemeConfiguration $theme,
        AbstractTestCase $testCase)
    {
        $this->themeConfiguration = $theme;
        $this->testCase = $testCase;
    }
    
    public function setWebDriver(WebDriver $webdriver)
    {
        $this->webdriver = $webdriver;
    }
    
    public function navigateTo(array $instructions)
    {
        foreach ($instructions as $instruction) {
            $this->testCase->assertCount(2, $instruction, 'Navigation instructions need to be a 2 member array.  First item is the instruction type, the second is the XPath');
            list($instruction, $xpath) = $instruction;
            $element = $this->webdriver->byXpath($xpath);
            $this->testCase->assertNotNull($element);
            $this->testCase->assertTrue($element->isDisplayed());
            
            switch ($instruction) {
                case WebDriver::INSTRUCTION_MOUSE_CLICK: 
                    $this->webdriver->getMouse()->click($element->getCoordinates());
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
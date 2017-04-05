<?php

namespace Magium\Navigators;

use Facebook\WebDriver\Exception\ElementNotVisibleException;
use Facebook\WebDriver\WebDriverBy;
use Magium\AbstractTestCase;
use Magium\Actions\WaitForPageLoaded;
use Magium\InvalidConfigurationException;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class InstructionNavigator implements NavigatorInterface
{
    const NAVIGATOR = 'InstructionNavigator';

    const INSTRUCTION_MOUSE_CLICK           = 'mouseClick';
    const INSTRUCTION_MOUSE_MOVETO          = 'mouseMoveTo';
    const INSTRUCTION_WAIT_FOR_DISPLAYED    = 'waitForDisplayed';
    const INSTRUCTION_WAIT_FOR_EXISTS       = 'waitForExists';
    const INSTRUCTION_WAIT_FOR_NOT_EXISTS   = 'waitForNotExists';
    const INSTRUCTION_WAIT_FOR_HIDDEN       = 'waitForHidden';
    const INSTRUCTION_PAUSE                 = 'pause';
    const INSTRUCTION_USE_MANUAL_TIMING     = 'manualTiming';

    protected $webdriver;
    protected $testCase;
    protected $loaded;
    
    public function __construct(
        AbstractTestCase $testCase,
        WebDriver $webdriver,
        WaitForPageLoaded $loaded
    )
    {
        $this->testCase             = $testCase;
        $this->webdriver            = $webdriver;
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
        $this->testCase->assertGreaterThan(0, count($instructions), 'Instruction navigator requires at least one instruction');
        $useAutomaticTiming = true;
        foreach ($instructions as $key => $instruction) {
            if (count($instruction) > 0 && $instruction[0] == self::INSTRUCTION_USE_MANUAL_TIMING) {
                $useAutomaticTiming = false;
            }
        }

        foreach ($instructions as $instruction) {
            $this->testCase->assertCount(2, $instruction, 'Navigation instructions need to be a 2 member array.  First item is the instruction type, the second is the XPath');
            list($instruction, $xpath) = $instruction;
            if ($useAutomaticTiming) {
                $this->testCase->sleep('100ms'); // Courtesy sleep of 100ms
                if ($instruction == self::INSTRUCTION_MOUSE_MOVETO || $instruction == self::INSTRUCTION_MOUSE_CLICK) {
                    $this->webdriver->wait()->until(ExpectedCondition::elementExists($xpath, WebDriver::BY_XPATH));
                    $this->webdriver->wait(5)->until(ExpectedCondition::elementToBeClickable(WebDriverBy::xpath($xpath)));
                }
            }
            
            switch ($instruction) {
                case self::INSTRUCTION_MOUSE_CLICK:
                    $element = $this->webdriver->byXpath($xpath);
                    if (!$element->isDisplayed()) {
                        throw new ElementNotVisibleException('The element is not visible: ' . $xpath);
                    }
                    $element->click();
                    break;
                case self::INSTRUCTION_MOUSE_MOVETO:
                    $element = $this->webdriver->byXpath($xpath);
                    $this->webdriver->getMouse()->mouseMove($element->getCoordinates());
                    break;
                case self::INSTRUCTION_WAIT_FOR_EXISTS:
                    $this->webdriver->wait()->until(ExpectedCondition::elementExists($xpath, WebDriver::BY_XPATH));
                    break;
                case self::INSTRUCTION_WAIT_FOR_NOT_EXISTS:
                    $this->webdriver->wait()->until(
                        ExpectedCondition::not(
                            ExpectedCondition::elementExists( $xpath, WebDriver::BY_XPATH)
                        )
                    );
                    break;
                case self::INSTRUCTION_WAIT_FOR_DISPLAYED:
                    $element = $this->webdriver->byXpath($xpath);
                    $this->webdriver->wait()->until(ExpectedCondition::visibilityOf($element));
                    break;
                case self::INSTRUCTION_PAUSE:
                    $this->testCase->sleep($xpath);
                    break;
                case self::INSTRUCTION_WAIT_FOR_HIDDEN:
                    $element = $this->webdriver->byXpath($xpath);
                    $this->webdriver->wait()->until(
                        ExpectedCondition::not(
                            ExpectedCondition::visibilityOf(
                                $element
                            )
                        )
                    );
                    break;
                default:
                    throw new InvalidConfigurationException('Unknown login instruction: ' .$instruction );
            }
        }

    }
    
}

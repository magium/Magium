<?php

namespace Magium\WebDriver;

use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\HttpCommandExecutor;
use Facebook\WebDriver\Remote\RemoteExecuteMethod;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverCommand;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverCommandExecutor;
use Facebook\WebDriver\WebDriverElement;
use Magium\Util\Log\Logger;
use Magium\Util\Log\LoggerAware;


class WebDriver extends RemoteWebDriver implements LoggerAware
{
    const INSTRUCTION_MOUSE_MOVETO = 'mouseMoveTo';
    const INSTRUCTION_MOUSE_CLICK  = 'mouseClick';

    const BY_XPATH = 'byXpath';
    const BY_ID    = 'byId';
    const BY_CSS_SELECTOR = 'byCssSelector';

    /**
     * @var Logger
     */

    protected $logger;

    protected $browser;
    protected $platform;

    /**
     * @return mixed
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * @return mixed
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    public function setCommandExecutor(WebDriverCommandExecutor $executor)
    {
        $command = new WebDriverCommand($this->getSessionID(), DriverCommand::GET_CAPABILITIES, []);
        $result = $executor->execute($command);
        $values = $result->getValue();
        $this->browser = $values['browserName'];
        $this->platform = $values['platform'];
        return parent::setCommandExecutor($executor);
    }


    public function logFind($by, $selector)
    {
        if ($this->logger instanceof Logger) {
            $this->logger->debug(
                sprintf(
                    'By: %s %s',
                    $by,
                    $selector
                ),
                [
                    'type' => 'webdriver-activity',
                    'activity'  => 'select',
                    'by'        => $by,
                    'selector'  => $selector
                ]
            );
        }
    }

    public function logElement(WebDriverElement $element)
    {
        if ($this->logger instanceof Logger) {
            $this->logger->debug(
                sprintf(
                    'Returned element: %s ID: %s',
                    get_class($element),
                    $element->getID()
                ),
                [
                    'type'      => 'webdriver-activity',
                    'activity'  => 'element-return',
                    'type'      => get_class($element),
                    'id'        => $element->getID()
                ]
            );
        }
    }

    public function setRemoteExecuteMethod(LoggingRemoteExecuteMethod $method)
    {
        $this->executeMethod = $method;
    }

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param WebDriverBy $by
     * @return \Facebook\WebDriver\Remote\RemoteWebElement[]
     */

    public function findElements(WebDriverBy $by)
    {
        $this->logFind($by->getMechanism(), $by->getValue());
        $elements = parent::findElements($by);
        foreach ($elements as $element) {
            $this->logElement($element);
        }
        return $elements;
    }

    /**
     * @param WebDriverBy $by
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */

    public function findElement(WebDriverBy $by)
    {
        $this->logFind($by->getMechanism(), $by->getValue());
        $element = parent::findElement($by);
        $this->logElement($element);
        return $element;
    }

    public function elementExists($selector, $by = 'byId')
    {
        try {
            $this->$by($selector);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function elementAttached(WebDriverElement $element)
    {
        try {
            $element->isDisplayed();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function elementDisplayed($selector, $by = 'byId')
    {
        try {
            $element = $this->$by($selector);
            return $element->isDisplayed();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 
     * @param string $xpath
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    
    public function byXpath($xpath)
    {
        return $this->findElement(WebDriverBy::xpath($xpath));
    }
    
    /**
     * 
     * @param string $id
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    
    public function byId($id)
    {
        return $this->findElement(WebDriverBy::id($id));
    }
    
    /**
     * 
     * @param string $selector
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    
    public function byCssSelector($selector)
    {
        return $this->findElement(WebDriverBy::cssSelector($selector));
    }
    
    public function __destruct()
    {
        try {
            if ($this->getCommandExecutor() instanceof HttpCommandExecutor) {
                $this->quit();
            }
        } catch (WebDriverException $e) {
            // Not a problem.  It just means that the WebDriver session was closed somewhere else
        }
    }

}

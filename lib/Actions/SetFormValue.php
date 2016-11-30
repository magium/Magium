<?php


namespace Magium\Actions;

use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverSelect;
use Magium\NotFoundException;
use Magium\WebDriver\WebDriver;

class SetFormValue implements ActionInterface
{
    const ACTION = "SetFormValue";

    protected $webDriver;

    public function __construct(
        WebDriver $webDriver
    )
    {
        $this->webDriver = $webDriver;
    }

    public function setByLabel($label, $value)
    {
        $this->set($label, $value);
    }

    public function setById($id, $value)
    {
        $this->set('id:' . $id, $value);
    }

    public function set($name, $value)
    {
        $formElement = null;
        $pos = strpos($name, 'id:');
        if ($pos === 0) {
            $formElement = $this->webDriver->byId(substr($name, 3));
        } else {
            $element = $this->webDriver->byXpath(sprintf('//label[.="%s"]', $name));
            if (!$element->getAttribute('for')) {
                throw new NotFoundException('Unable to find the "for" attribute for a label with the text: ' . $name);
            }
            $formElement = $this->webDriver->byId($element->getAttribute('for'));
        }

        $type = strtolower($formElement->getTagName());
        switch ($type) {
            case 'select':
                $this->setSelect($formElement, $value);
                break;
            default:
                $type = strtolower($formElement->getAttribute('type'));
                switch ($type) {
                    case 'radio':
                        $this->setClicked($formElement, $value);
                        break;
                    case 'checkbox':
                        $this->setClicked($formElement, $value);
                        break;
                    default:
                        $this->setText($formElement, $value);
                }

        }
    }

    protected function setText(WebDriverElement $element, $value)
    {
        $element->clear();
        $element->sendKeys($value);

    }

    protected function setClicked(WebDriverElement $element, $value)
    {
        $checked = (bool)$element->getAttribute('checked');
        if ((bool)$checked != (bool)$value) {
            $element->click();
        }
    }

    protected function setSelect(WebDriverElement $element, $value)
    {
        $select = new WebDriverSelect($element);
        if ($select->isMultiple()) {
            $select->deselectAll();
        }
        if (!is_array($value)) {
            $value = [$value];
        }
        foreach ($value as $v) {
            if (strpos($v, 'value:') === 0) {
                $select->selectByValue(substr($value, 6));
            } else {
                $select->selectByVisibleText($v);
            }
        }
    }

    public function execute($name, $value)
    {
        $this->set($name, $value);
    }

}

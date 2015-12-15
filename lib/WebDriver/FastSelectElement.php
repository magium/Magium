<?php

namespace Magium\WebDriver;

class FastSelectElement
{

    protected $webDriver;
    protected $xpath;

    public function __construct(WebDriver $webDriver, $xpath)
    {
        $this->webDriver = $webDriver;
        $this->xpath = str_replace('"', '\"', $xpath);
    }

    public function getOptions()
    {
        $javascript = sprintf('
var select = document.evaluate("%s", document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
var selectedOptions = [];
for (var i=0, n=select.options.length;i<n;i++) {
    selectedOptions.push({
        label: select.options[i].text,
        value: select.options[i].value
    });
}
return selectedOptions;
', $this->xpath);
        $result = $this->webDriver->executeScript($javascript);
        return $result;
    }

    public function clearSelectedOptions()
    {
        $javascript = sprintf('
var select = document.evaluate("%s", document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
select.selectedIndex = -1;
', $this->xpath);
        $return = $this->webDriver->executeScript($javascript, []);
        return $return;
    }

    public function getSelectedOptions()
    {

        $javascript = sprintf('
var select = document.evaluate("%s", document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
var selectedOptions = [];
for (var i=0, n=select.options.length;i<n;i++) {
    if (select.options[i].selected)  {
        selectedOptions.push({
            label: select.options[i].text,
            value: select.options[i].value
        });
    }
}
return selectedOptions;
', $this->xpath);
        $result = $this->webDriver->executeScript($javascript);
        return $result;
    }

}
<?php

namespace Magium\Extractors;


abstract class AbstractAddressExtractor extends AbstractExtractor
{

    protected $name;
    protected $business;
    protected $street1;
    protected $street2;
    protected $city;
    protected $region;
    protected $postCode;
    protected $country;
    protected $phone;
    protected $fax;

    protected $remainingRows;

    abstract public function getBaseXpath();

    public function extract()
    {
        $addressElement = $this->webDriver->byXpath($this->getBaseXpath());
        $text = $addressElement->getText();
        $rows = explode("\n", $text);

        $rows = array_reverse($rows);
        $this->name = trim(array_pop($rows));
        while (count($rows) > 0) {
            $row = array_shift($rows);
            if (strpos($row, 'T: ') ===0) {
                $this->phone = trim(substr($row, 3));
                continue;
            } else if (strpos($row, 'F: ') ===0) {
                $this->fax = trim(substr($row, 3));
                continue;
            }
            if ($this->country === null) {
                $this->country = trim($row);
                continue;
            }
            if ($this->postCode === null) {
                $parts = explode(',', $row);
                $this->postCode = trim(array_pop($parts));
                $this->city = trim(array_shift($parts));
                if (count($parts) > 0) {
                    $this->region = trim(array_shift($parts));
                }
                break; // After this point we have to figure out business, st1 and st2; two of which are not required
            }

        }
        // Easy
        if (count($rows) == 3) {
            $this->street2 = trim(array_shift($rows));
            $this->street1 = trim(array_shift($rows));
            $this->business = trim(array_shift($rows));
            //Easy
        }else if (count($rows) == 1) {
            $this->street1 = trim(array_shift($rows));

            // Not so easy
        } else {
            $stOrBus = trim(array_shift($rows));
            if (preg_match('/^\d+ /', $stOrBus)) { // Good chance of street 1 (could be a business, though)

                $this->street1 = $stOrBus;
                $this->business = trim(array_shift($rows));
            } else {
                $this->street1 =trim(array_shift($rows));
                $this->street2 =  $stOrBus;
            }

        }
        $this->remainingRows = $rows;

    }

    /**
     * @return mixed
     */
    public function getRemainingRows()
    {
        return $this->remainingRows;
    }



    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @return mixed
     */
    public function getStreet1()
    {
        return $this->street1;
    }

    /**
     * @return mixed
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return mixed
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return mixed
     */
    public function getFax()
    {
        return $this->fax;
    }

}
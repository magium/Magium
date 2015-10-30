<?php

namespace Magium;

use Zend\Di\Di;
interface DiAware
{
    
    public function setDi(Di $di);
    
}
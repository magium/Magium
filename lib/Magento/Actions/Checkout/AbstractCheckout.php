<?php

namespace Magium\Magento\Actions\Checkout;

use Magium\Magento\Actions\Checkout\Steps\StepInterface;
abstract class AbstractCheckout
{
    
    protected $steps = [];
    
    public function addStep(StepInterface $step, $before = null)
    {
        if ($before === null) {
            $this->steps[] = $step;
            return;
        }
        $key = array_search($step, $this->steps);
        if ($key !== false) {
            array_splice($this->steps, $key, 0, $step);
        } else {
            $this->steps[] = $step;
        }
        
    }
    
    public function getStepInstance($class)
    {
        foreach ($this->steps as $step) {
            if ($step instanceof $class) {
                return $step;
            }
        }
    }
    
    public function execute()
    {
        foreach ($this->steps as $step) {
            $continue = $step->execute();
            if (!$continue) return;
        }
    }
    
}
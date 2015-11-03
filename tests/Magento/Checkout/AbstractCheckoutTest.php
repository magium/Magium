<?php

namespace Tests\Magento\Checkout;

class AbstractCheckoutTest extends \PHPUnit_Framework_TestCase
{
    
    public function testStepAddedAndCanBeRetrieved()
    {
        $abstractCheckout = $this->getMockBuilder('Magium\Magento\Actions\Checkout\AbstractCheckout')->setMethods(null)->getMock();
        /* @var $abstractCheckout \Magium\Magento\Actions\Checkout\AbstractCheckout */
        
        $step = $this->getMock('Magium\Magento\Actions\Checkout\Steps\StepInterface');
        
        $abstractCheckout->addStep($step);
        $getStep = $abstractCheckout->getStepInstance(get_class($step));
        self::assertInstanceOf(get_class($step), $getStep);
        
    }
    
    public function testStepWillBeExecuted()
    {
        $abstractCheckout = $this->getMockBuilder('Magium\Magento\Actions\Checkout\AbstractCheckout')->setMethods(null)->getMock();
        /* @var $abstractCheckout \Magium\Magento\Actions\Checkout\AbstractCheckout */
        
        $step = $this->getMockBuilder('Magium\Magento\Actions\Checkout\Steps\StepInterface')->setMethods(['execute'])->getMock();
        $step->expects(self::once())->method('execute')->willReturn(true);
        
        $abstractCheckout->addStep($step);
        $abstractCheckout->execute();
        
    }
    
    public function testStepReturningFalseMakesExecutionStop()
    {
        $abstractCheckout = $this->getMockBuilder('Magium\Magento\Actions\Checkout\AbstractCheckout')->setMethods(null)->getMock();
        /* @var $abstractCheckout \Magium\Magento\Actions\Checkout\AbstractCheckout */
        
        $step1 = $this->getMockBuilder('Magium\Magento\Actions\Checkout\Steps\StepInterface')->setMethods(['execute'])->getMock();
        $step1->expects(self::once())->method('execute')->willReturn(false);
        $step2 = $this->getMockBuilder('Magium\Magento\Actions\Checkout\Steps\StepInterface')->setMethods(['execute'])->getMock();
        $step2->expects(self::never())->method('execute')->willReturn(true);
        
        $abstractCheckout->addStep($step1);
        $abstractCheckout->addStep($step2);
        $abstractCheckout->execute();
        
    }
    
    public function testStepWillBeExecutedBeforeAnotherStep()
    {
        $abstractCheckout = $this->getMockBuilder('Magium\Magento\Actions\Checkout\AbstractCheckout')->setMethods(null)->getMock();
        /* @var $abstractCheckout \Magium\Magento\Actions\Checkout\AbstractCheckout */
        
        $count = 0;
        $countIsOne = false;
        $step1 = $this->getMockBuilder('Magium\Magento\Actions\Checkout\Steps\StepInterface')->setMethods(['execute'])->getMock();
        $step1->expects(self::once())->method('execute')->willReturnCallback(function() use (&$count, &$countIsOne) {
            $countIsOne = $count === 1;
            return true;
        });
        
        $step2 = $this->getMockBuilder('Magium\Magento\Actions\Checkout\Steps\StepInterface')->setMethods(['execute'])->getMock();
        $step2->expects(self::once())->method('execute')->willReturnCallback(function() use (&$count) {
            $count++;
            return true;
        });
        
        $abstractCheckout->addStep($step2);
        $abstractCheckout->addStep($step1, $step2);
        $abstractCheckout->execute();
        self::assertTrue($countIsOne, 'Counter was not incremented');
    }
    
}
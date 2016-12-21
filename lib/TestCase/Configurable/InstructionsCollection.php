<?php

namespace Magium\TestCase\Configurable;

use Magium\Assertions\AssertInterface;
use Magium\Assertions\Element\AbstractSelectorAssertion;
use Magium\Util\Log\Logger;
use Zend\Di\Di;

class InstructionsCollection
{

    protected $instructions = [];
    protected $container;
    protected $interpolator;
    protected $logger;

    public function __construct(
        Di $container,
        Interpolator $interpolator,
        Logger $logger
    )
    {
        $this->container = $container;
        $this->interpolator = $interpolator;
        $this->logger = $logger;
    }

    public function addInstruction(InstructionInterface $instruction)
    {
        $this->instructions[] = $instruction;
    }

    public function execute()
    {
        foreach ($this->instructions as $instruction) {
            if ($instruction instanceof InstructionInterface) {
                $instance = $this->container->get($instruction->getClassName());
                $callback = [$instance, $instruction->getMethod()];
                if (!is_callable($callback)) {
                    throw new InvalidInstructionException('Unable to execute instruction');
                }
                $params = [];
                $callParams = $instruction->getParams();
                if ($callParams) {
                    $params = $callParams;
                }
                $this->logger->info(sprintf('Executing %s', $instruction->getClassName()), [
                    'class' => get_class($instance),
                    'params'    => json_encode($callParams)
                ]);
                try {
                    call_user_func_array($callback, $params);
                } catch (\Exception $e) {

                    $this->logger->err($e->getMessage());
                    throw $e;
                }
                $this->logger->info(sprintf('%s completed', $instruction->getClassName()));
            }
        }
    }


}

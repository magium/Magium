<?php

namespace Magium\TestCase\Configurable;

use Zend\Di\Di;

class InstructionsCollection
{

    protected $instructions = [];
    protected $container;
    protected $interpolator;

    public function __construct(
        Di $container,
        Interpolator $interpolator
    )
    {
        $this->container = $container;
        $this->interpolator = $interpolator;
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
                call_user_func_array($callback, $params);
            }
        }
    }


}
<?php

namespace Magium\TestCase\Configurable;

use Magium\Util\Log\Logger;
use Zend\Di\Di;

class Interpolator
{

    protected $container;
    protected $logger;

    public function __construct(
        Di $di,
        Logger $logger
    )
    {
        $this->container = $di;
        $this->logger = $logger;
    }

    public function interpolate($string)
    {
        $this->logger->info('Interpolating string: ' . $string);
        $matches = null;
        // Finding {{$product->setEntityId(123)->load()}} type methods
        if (preg_match('/\{\{(?:\$)([^{]+)\}\}/', $string, $matches)) {
            array_shift($matches);
            foreach ($matches as $match) {
                $result = null;
                // Just replace it if it is a straight value reference
                if (strpos($match, '->') === false) {
                    $result = $this->container->get($match);
                } else {
                    // However, if it contains object method reference tokens let's recursively call them!  So much good
                    $parts = explode('->', $match);
                    $classOrAlias = array_shift($parts);
                    $result = $this->container->get($classOrAlias);
                    foreach ($parts as $method) {
                        $params = [];
                        $matchedParams = null;
                        if (preg_match('/\(([^)]+)\)/', $method, $matchedParams)) {
                            array_shift($matchedParams); // Don't need the full match
                            $matchedParams = array_shift($matchedParams); // Should only need the first match
                            $params = explode(',', $matchedParams);
                            $params = array_map('trim', $params);
                        }

                        if (strpos($method, '(') === false) {
                            $this->logger->debug(sprintf('Reading property %s->%s', get_class($result), $method));
                            $result = $result->$method;
                        } else {
                            $method = preg_replace('/\([^\)]*\)/', '', $method);
                            $callback = [$result, $method];
                            $this->logger->debug(
                                sprintf('Calling %s->%s with params %s', get_class($result), $method, serialize($params))
                            );
                            if (is_callable($callback)) {
                                $result = call_user_func_array($callback, $params);
                            } else {
                                throw new InvalidInstructionException('Result is not callable');
                            }
                        }
                    }
                }
                $result = (string)$result;
                $this->logger->info(sprintf('Replacing %s with %s', $match, $result));
                $search = sprintf('{{$%s}}', $match);
                $string = str_replace($search, $result, $string);
            }
        } else {
            $this->logger->debug('No interpolations found');
        }
        return $string;
    }

}

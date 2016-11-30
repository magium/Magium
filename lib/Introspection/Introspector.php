<?php

namespace Magium\Introspection;

use Magium\Actions\ActionInterface;
use Magium\Actions\ConfigurableActionInterface;
use Magium\Actions\OptionallyConfigurableActionInterface;
use Magium\Actions\StaticActionInterface;
use Magium\Assertions\AssertInterface;
use Magium\Assertions\AssertionInterface;
use Magium\Assertions\SelectorAssertionInterface;
use Magium\Commands\CommandInterface;
use Magium\Extractors\ExtractorInterface;
use Magium\Identities\IdentityInterface;
use Magium\Navigators\ConfigurableNavigatorInterface;
use Magium\Navigators\NavigatorInterface;
use Magium\Navigators\OptionallyConfigurableNavigatorInterface;
use Magium\Navigators\StaticNavigatorInterface;
use Magium\Themes\ThemeInterface;
use Magium\Util\Log\Logger;

class Introspector
{
    protected $identifiers = [
        IdentityInterface::class,
        ExtractorInterface::class,
        CommandInterface::class,
        AssertInterface::class,
        ActionInterface::class,
        NavigatorInterface::class,
        ThemeInterface::class
    ];

    protected $typePreferences = [
        AssertInterface::class => [
            SelectorAssertionInterface::class,
            AssertionInterface::class
        ],
        ActionInterface::class => [
            ConfigurableActionInterface::class,
            OptionallyConfigurableActionInterface::class,
            StaticActionInterface::class
        ],
        NavigatorInterface::class => [
            ConfigurableNavigatorInterface::class,
            OptionallyConfigurableNavigatorInterface::class,
            StaticNavigatorInterface::class
        ]
    ];

    protected $logger;

    public function __construct(
        Logger $logger
    )
    {
        $this->logger = $logger;
    }

    public function introspect($paths)
    {
        if (!is_array($paths)) {
            $paths = [$paths];
        }
        $classes = [];
        foreach ($paths as $path) {
            $this->logger->info(sprintf('Examining path: %s', $path));
            $result = $this->scanPath($path);
            $classes = array_merge($classes, $result);
        }
        return $classes;
    }

    protected function scanPath($path)
    {
        $classes = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            /* @var $file \SplFileInfo */
            if ($file->isFile() && $file->getExtension() == 'php') {
                $filePath = $file->getRealPath();
                $className = $this->getClassNameInFile($filePath);
                if (!$className) {
                    $this->logger->err(sprintf('Could not extract class from PHP file: %s', $filePath));
                } else {
                    $result = $this->processClass($className);
                    if ($result instanceof ComponentClass) {
                        $classes[$result->getClass()] = $result;
                        $this->logger->info(sprintf('Introspected Magium component class %s', $result->getClass()));
                    }
                }
            }
        }
        return $classes;
    }

    protected function processClass($class)
    {
        try {
            $reflectionClass = new \ReflectionClass($class);
        } catch (\Exception $e) {
            $this->logger->err($e->getMessage());
            return null;
        }
        if (!$reflectionClass->isInstantiable()) {
            return null;
        }
        foreach ($this->identifiers as $identifier) {
            if ($reflectionClass->implementsInterface($identifier)) {
                $name = $reflectionClass->getName();
                $baseType = $functionalType = $identifier;
                if (isset($this->typePreferences[$identifier])) {
                    foreach ($this->typePreferences[$identifier] as $preference) {
                        if ($reflectionClass->implementsInterface($preference)) {
                            $functionalType = $preference;
                            break;
                        }
                    }
                }
                $hierarchy = [];
                $rClass = $reflectionClass;
                while ($rClass && ($pClass = $rClass->getParentClass()) != false) {
                    $hierarchy[] = $pClass->getName();
                    $rClass = $pClass->getParentClass();
                }
                $hierarchy[] = $functionalType;
                $hierarchy[] = $baseType;
                $hierarchy = array_unique($hierarchy);
                $componentClass = new ComponentClass($name, $baseType, $functionalType, $hierarchy);
                $this->logger->debug('Extracted Magium component', ['object' => serialize($componentClass)]);
                return $componentClass;
            }
        }
        $this->logger->debug(sprintf('File %s was not a Magium component class', $class));
        return null;
    }

    protected function getClassNameInFile($file)
    {
        $namespace = $class = '';
        $content = file_get_contents($file);
        if (!$content) return null;
        $tokens = token_get_all($content);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if ($tokens[$i - 2][0] == T_CLASS
                && $tokens[$i - 1][0] == T_WHITESPACE
                && $tokens[$i][0] == T_STRING
            ) {

                $class = $namespace . $tokens[$i][1];
                $this->logger->debug(sprintf('Extracted %s from %s', $class, $file));
                return $class;
            } else if ($tokens[$i - 2][0] == T_NAMESPACE
                && $tokens[$i - 1][0] == T_WHITESPACE
                && $tokens[$i][0] == T_STRING) {

                $plus = 0;
                while ($tokens[$i+$plus] != ';') {
                    $token = $tokens[$i+$plus];
                    if ($token[0] == T_STRING) {
                        $namespace .= $token[1] . '\\';
                    }
                    $plus++;
                }

                $this->logger->debug(sprintf('Extracted namespace %s from %s', $namespace, $file));
            }
        }
        return null;

    }
}

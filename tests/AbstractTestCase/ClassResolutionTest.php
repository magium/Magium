<?php

namespace Tests\Magium\AbstractTestCase {

    use Magium\AbstractTestCase;
    use Magium\Navigators\Home;

    class ClassResolutionTest extends AbstractTestCase
    {
        public function testGetHomeWhichIsActuallyTestingClassResolution()
        {

            $this->switchThemeConfiguration('Magium\BaseTheme');
            self::assertInstanceOf('Magium\Navigators\Home', $this->getNavigator(Home::NAVIGATOR));
        }

        public function testAutoloaderWithExceptionThrownIsCaught()
        {
            $thrown = false;

            $autoloadFunction = function() use (&$thrown) {
                $thrown = true;
                throw new \Exception('This should be thrown but caught');
            };

            spl_autoload_register($autoloadFunction);

            self::resolveClass('boogers_or_something_else_that_does_not_exist');

            spl_autoload_unregister($autoloadFunction);

            // The next line of code will not be reached if an exception is thrown, but not caught.
            self::assertTrue($thrown);

        }
    }
}

namespace Magium {

    use Magium\Themes\BaseThemeInterface;

    class BaseTheme implements BaseThemeInterface {
        public function getHomeXpath()
        {
            return null;
        }

        public function configure(AbstractTestCase $testCase)
        {
            return null;
        }

        public function getGuaranteedPageLoadedElementDisplayedXpath()
        {
            return null;
        }
        public function setGuaranteedPageLoadedElementDisplayedXpath($value)
        {
            return null;
        }
    }
}

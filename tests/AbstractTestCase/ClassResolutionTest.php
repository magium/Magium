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
    }
}

namespace Magium {

    use Magium\Themes\BaseThemeInterface;

    class BaseTheme implements BaseThemeInterface {
        public function getHomeXpath()
        {
            return null;
        }

        public function getGuaranteedPageLoadedElementDisplayedXpath()
        {
            return null;
        }
    }
}
    # Magium
    A PHPUnit/Webdriver based test framework for streamlining browser testing with Magento


    # Rules for test utility classes

     * All calls will presume positive results.  If you wish to test negative results you will need to build out that functionality in code yourself
     * All calls will presume a pre-existing web page that is opened.  They will expect you to "set the stage" for the functionality. For example, if you want to navigate to a particular category page, the test is going to presume that the page it is on has that functionality on it.
     * Whenever possible (hopefully 100% of the time) the DI container in the test case itself should be used to retrieve test objects. Some commonly used functionality, such as opening a web page, will have pre-existent convenience methods defined.
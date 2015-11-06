# Magium
A PHPUnit/Webdriver (Selenium (there for SEO)) based test framework for streamlining browser testing with Magento.  It basically boils down to building out reusable Web Driver commands for Magento allowing you to bypass much of the boilerplate code that browser/functional testing may require.  This is done through utility classes.

The baseline classes are built to the Magento 1.9 CE theme and sample data.  _Refactoring may occur if it is necessary to make the code work with multiple themes_.  If you want to contribute (please do!!) please make sure that your contributions work with, at least, Magento 1.9 CE with sample data.

The codebase as a whole uses dependency injection and a dependency injection container as part of a PHPUnit test.  Yes, that's correct. The tests use DI and a DIC.  Why would I commit such a horrible crime against the software development gods?  Well, because this is basically a glorified batch processing system.  But there are features in a DiC that allow you to make a lot of customizations. Say you want to build a test that adds to the cart, but there is some kind of popup that is non-standard.  Instead of building out all of this functionality yourself just extend the AddToCart action class, override the appropriate method and set a type preference in the di.php configuration file.  Does another site do something different?  Just extend AddToCart with a different class and re-use everything else.

Another benefit of using a DIC is that it allows you to easily modify test steps prior to their being executed.  All objects are shared objects in the DIC and so if you wanted to use a generated email address, for example, you could do something like this.

```php
class TestEmailAddress extends AbstractMagentoTestCase
{

    public function testGuestCheckout()
    {
        // Other stuff

        $customer = $this->get('Magium\Magento\Identities\CustomerIdentity');
        $customer->setEmailAddress('myemailaddress@gmail.com');

        $guestCheckout = $this->get('Magium\Magento\Actions\Checkout\GuestCheckout');
        $guestCheckout->execute();
    }

}
```

Using this code the guest checkout will then use that generated email address instead of the default "test@example.com".  This could be used to test the email that is sent out by having it sent to your GMail account.

Is this the best, most purest way of doing this?  I don't know.  But I was tasked with building out some very complex Selenium tests a while back and this is what I wish I had.

Why are you using PHPUnit?
==========================

Isn't PHPUnit for testing units of code?  Isn't this done in blatant disregard to pure software development?  Yes!  But for several reasons.

* You probably already know much of PHPUnit's syntax
* PHPUnit is most likely already integrated with your existing IDE, meaning you can run tests from directly in there.  It is also already integrated with a number of other development and testing tools.
* It's mature, with many different options for customizing its behavior.  There is some hard coded functionality (which is also a reason why I had to go with a DiC), but most of what you need is either already there or easily installed.
* A Selenium test needs assertions.  Was the product actually added to the cart?  Was I actually logged in, etc.

Basic Concepts
==============

The goal of Magium is to minimize boilerplate and maximize re-use.  So if a pattern doesn't quite make sense try to apply it to those two conditions and hopefully it will be a little more clear.

There are several base concepts that you need to be familiar with

* Commands - These tend to be basic WebDriver commands, such as open().  There aren't many of them.
* Actions - These are sets of commands that do strings of things to get to a particular goal.  One goal could be logging in.  Another goal could be adding a product to the cart.  Another could be checking out
* Assertions - There are two types of assertions
    * Static assertions - These are the kinds of assertions that you find in your test case itself.  assertEquals(), assertTrue(), etc.
    * Programmable assertions - These are more complicated assertions that may be programmable
* Identities - Used to manage users, such as a customer or admin user.
* Navigators - These classes will navigate to some place on the web site, but will not do any actions.  They are intended to be read-only, in other words.  Navigating to a product page.  Navigate to a customer login page.  Navigate to and admin System Configuration panel.  And so on.
* Themes - These are possibly mis-named.  They are intended to contain configuration information about your specific Magento instance.  So, if your customization has a different add-to-cart button you would either override these classes or provide configuration for internal use.
* Extractors - These are classes that can be inserted in some execution flow to extract data from the page.  Useful for assertions or for gathering data for further command execution.

As a matter of standardization Magium will use Xpath exclusively for internal functionality when the functionality is configurable  So if you intend to modify how Magium selects elements you will need to use Xpath to select elements.  If the item is not configurable (such as the selector IDs for payment methods) then whatever gives the most exact match will be used.

However, in your own tests, I would consider it a best practice to use the XML id as a first resort, CSS selectors second, and Xpath third.  But I also expect that the use cases for CSS selectors will actually be a sliver of a window.  They have the specificity of ID-based, but its a query, not an identifier, and so it is quite possible to fat-finger something in a way that leaves you wonder.  CSS selectorss also have the ability for DOM traversal like Xpath, but its functionality significantly limited, particularly if you are looking for a specific element among many.  As such, my personal preference, based off of my own experience, is to use XML IDs first, and then just use Xpath.  Once you are comfortable with basic Xpath you will find that its wealth of functionality is incredibly useful.

Configuration
=============

The system is intended to use as much configuration, instead of class overriding, as possible.  Class overrides should really only be used for non-standard customizations.

Not all classes can be configured.  But if they do they need to extend AbstractConfigurableElement.  The configuration for the object should be placed in configuration/Namespace/Class.php.  That file will be include()d during object instantialization and so any customizations should be done programmatically on $this.  See example_configuration_not_used for examples on how this looks.  Hint: it's not that hard.

But there is more configuration that can be done.  As noted earlier, the entire implementation is dependent on dependency injection.  So if there are any customized classes that the action classes (for example) need to use instead of the base ones you can configure the DIC to prefer one implementation over another in the configuration/di.php class.  An example can be seen in example_configuration_not_used/di.php where the Stream log writer is set to be preferred over the standard Noop writer.

Basic Usage
===========

Each test case class should extend Magium\AbstractTestCase.  This class contains bootstrapping to load up WebDriver, configure the DIC and do a few other setup things.  It also contains some additional helper functionality such as simplified element retrieval, Selenium level assertions (assertElementExists()) among other things.

Building a test case is quite simple.

```php

class TestHomePageTitle extends Magium\AbstractTestCase
{
    public function testTitleExists()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->assertElementExists('//title', self::BY_XPATH);
    }

}

```

Negative tests can be run too.

```php

class TestHomePageTitle extends Magium\AbstractTestCase
{

    public function testBadTitleNotExists()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        // You shouldn't have a title that equals "boogers".  That's just rude.
        $this->assertElementNotExists('//title[.="boogers"]', self::BY_XPATH);
    }

}
```

Rules for test utility classes
==============================

* All calls will presume positive results.  For example, it will presume that if you are intending to add something to the cart, you are intending to actually have it added to the cart.  If you wish to test negative results you will need to build out that functionality in code yourself
    * But that said there are some classes, such as the checkout classes, which are intended to be integrated with or have their execution modified programmatically.
* All calls will presume a pre-existing web page that is opened.  They will expect you to "set the stage" for the functionality you intend to call. For example, if you want to navigate to a particular category page, the test is going to presume that the page it is on has that functionality on it.
* Whenever possible (hopefully 100% of the time) the DI container in the test case itself should be used to retrieve test objects. Some commonly used functionality, such as opening a web page, will have pre-existent convenience methods defined.
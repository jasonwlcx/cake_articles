<?php
namespace Facebook\WebDriver;
require_once('vendor/autoload.php');
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedConditions;
use PHPUnit\Framework\TestCase;

class ToDoTest extends TestCase {
    protected $driver;

    public function setUp() {
        $capabilities =  DesiredCapabilities::chrome();
        $host = "http://localhost:4444/wd/hub";
        $this->driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());
    }
    /** @test
     * must inlcude above as an annotation for phpunit to recognize as valid test to run
     * otherwise prefix test function with keyword 'test'.
     */
    public function navigationSteps() {
        try {
            print "\nNavigating to URL\n";
            $this->driver->get("http://localhost:8765/articles/index");
            // print the title of the current page
            print "The title is '" . $this->driver->getTitle() . "'\n";
            // print the URI of the current page
            print "The current URL is '" . $this->driver->getCurrentURL() . "'\n";
            $this->assertStringStartsWith("http://localhost:8765/articles/index", $this->driver->getCurrentURL());

            // we want cookies originated each run
            $this->driver->manage()->deleteAllCookies();
            $cookie = new Cookie('cookie_name', 'cookie_value');
            $this->driver->manage()->addCookie($cookie);
            $cookies = $this->driver->manage()->getCookies();
            print_r($cookies);

            $this->driver->findElement(WebDriverBy::linkText("First Post"))->click();
            print "Clicking First Post Article\n";
            // wait until the page is loaded
            sleep(3); // debug
            $this->driver->wait()->until(
                WebDriverExpectedCondition::titleContains('Articles')
            );
            // print the title of the current page
            print "The title is '" . $this->driver->getTitle() . "'\n";
            // print the URI of the current page
            print "The current URL is '" . $this->driver->getCurrentURL() . "'\n";
            $this->assertResponseOk();
            $this->assertStringStartsWith("http://localhost:8765/articles/view/first-post", $this->driver->getCurrentURL());

            // click the link
            $this->driver->findElement(WebDriverBy::linkText("Edit"))->click();

            // wait for all elements in container class
            sleep(3); // debug
            $this->driver->wait()->until(
                WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                    WebDriverBy::className('container'))
            );

            // if we've made it this far, assertions have passed and we'll set the score to pass.
            $this->addToAssertionCount(1);
        } catch (Exception $e) {
            // if we caught an exception along the way, an assertion failed and we'll set the score to fail.
            print "Caught Exception: " . $e->getMessage();
        }
    }
    public function tearDown() {
        $this->driver->quit();
    }

}
?>

<?php

namespace TestExt;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Yen\Http\Uri;

abstract class WebTestCase extends \PHPUnit_Framework_TestCase
{
    private $webdriver_url;
    private $web_driver;
    private $should_cc;
    private $ccid;

    protected $website_url;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $opts = parse_ini_file(TCDATA_PATH . '/web/connection.ini');
        $this->webdriver_url = Uri::createFromString($opts['webdriver_url']);
        $this->website_url = Uri::createFromString($opts['website_url']);
    }

    protected function createWebDriver()
    {
        $browser = DesiredCapabilities::chrome();
        return RemoteWebDriver::create($this->webdriver_url, $browser);
    }

    protected function getWebDriver()
    {
        return $this->web_driver;
    }

    protected function setUp()
    {
        try {
            $this->web_driver = $this->createWebDriver();
        } catch (\Exception $ex) {
            $this->markTestSkipped('Selenium server not available');
        };

        if ($this->should_cc) {
            $this->web_driver->get($this->website_url->withPath('/__cc/start'));
            $this->ccid = $this->web_driver->manage()->getCookieNamed('__ccid')['value'];
        };
    }

    protected function tearDown()
    {
        if ($this->web_driver) {
            $this->web_driver->quit();
            $this->web_driver = null;
        };
    }

    public function run(\PHPUnit_Framework_TestResult $result = null)
    {
        $this->should_cc = $result->getCollectCodeCoverageInformation();

        $result = parent::run($result);

        if ($this->should_cc && !$this->hasFailed()) {
            $url = $this->website_url
                        ->withPath('/__cc/get')
                        ->withJoinedQuery(['ccid' => $this->ccid]);
            $all_cc = json_decode(file_get_contents($url), true);
            $cc = $result->getCodeCoverage();
            foreach ($all_cc as $data) {
                $cc->append($data, $this);
            };
        };

        return $result;
    }
}

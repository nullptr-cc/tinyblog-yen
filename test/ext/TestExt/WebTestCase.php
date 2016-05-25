<?php

namespace TestExt;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class WebTestCase extends \PHPUnit_Framework_TestCase
{
    protected static $selenium_url;
    protected static $website_url;
    protected $wd;
    private $should_cc;
    private $ccid;

    public static function setUpBeforeClass()
    {
        $opts = parse_ini_file(TCDATA_PATH . '/web/connection.ini');
        self::$selenium_url = $opts['selenium_url'];
        self::$website_url = $opts['website_url'];
    }

    public function setUp()
    {
        $this->wd = RemoteWebDriver::create(self::$selenium_url, DesiredCapabilities::htmlUnitWithJS());

        if ($this->should_cc) {
            $this->wd->get(self::$website_url . '/__cc/start');
            $this->ccid = $this->wd->manage()->getCookieNamed('__ccid')['value'];
        };
    }

    public function tearDown()
    {
        $this->wd->quit();
        $this->wd = null;
    }

    public function run(\PHPUnit_Framework_TestResult $result = null)
    {
        $this->should_cc = $result->getCollectCodeCoverageInformation();

        $result = parent::run($result);

        if ($this->should_cc && !$this->hasFailed()) {
            $url = self::$website_url . '/__cc/get?ccid=' . $this->ccid;
            $all_cc = json_decode(file_get_contents($url), true);
            $cc = $result->getCodeCoverage();
            foreach ($all_cc as $data) {
                $cc->append($data, $this);
            };
        };

        return $result;
    }
}

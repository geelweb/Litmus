<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * Include test helper
 */
require_once dirname(__FILE__) . '/../TestHelper.php';

/**
 * Unit test class for Litmus Test Set Version Methods
 *
 * @package Litmus_Test
 */
class Litmus_TestSetVersionTest extends PHPUnit_Framework_TestCase
{
    public function testVersionsMethod()
    {
        $test = Litmus::getTests(1); 
        $versions = $test->getVersions();
        $this->assertTrue(is_array($versions));
        $this->assertTrue($versions[0] instanceof Litmus_Version);
    }

    public function testVersionsShowMethod()
    {
        $test = Litmus::getTests(1); 
        $version = $test->getVersions(1);
        $this->assertTrue($version instanceof Litmus_Version);
        $this->assertEquals($version->version, 1);
        $this->assertEquals($version->url_or_guid, 'http://google.com');
        $this->assertEquals($version->received, 'true');
        $this->assertTrue(is_array($version->results));
        $this->assertTrue($version->results[0] instanceof Litmus_Result);

        // try to get all results of the version
        $results = $version->getResults();
        $this->assertTrue(is_array($results));
        $this->assertTrue($results[0] instanceof Litmus_Result);
    }

    public function testVersionsCreateMethod()
    {
    }

    public function testVersionsPollMethod()
    {
    }
}
 

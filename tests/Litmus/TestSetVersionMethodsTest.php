<?php
/**
 *
 *
 * @package Litmus_UnitTest
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * Include test helper
 */
require_once __DIR__ . '/../TestHelper.php';

/**
 * Unit test class for Litmus Test Set Version Methods
 *
 * @package Litmus_UnitTest
 */
class Litmus_TestSetVersionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the versions RESTFul method
     *
     */
    public function testVersionsMethod()
    {
        $test = Litmus::getTests(1); 
        $versions = $test->getVersions();
        $this->assertTrue(is_array($versions));
        $this->assertTrue($versions[0] instanceof Litmus_Version);
    }

    /**
     * Test the versions/show RESTFul method
     *
     */
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

    /**
     * Test the versions/create RESTFul Method
     *
     */
    public function testVersionsCreateMethod()
    {
        $test = Litmus::getTests(1); 
        $nb_versions = count($test->test_set_versions);
        $version = $test->createVersion();
        $this->assertEquals(count($test->test_set_versions), $nb_versions+1);
        $this->assertTrue($version instanceof Litmus_Version);
    }

    /**
     * Test the versions/poll RESTFul method
     *
     */
    public function testVersionsPollMethod()
    {
        $test = Litmus::getTests(1); 
        $version = $test->getVersions(1);
        $version = $version->poll();

        $this->assertEquals($version->version, 1);
        $this->assertTrue(is_array($version->results));
        $this->assertTrue(1 == count($version->results));
        $this->assertTrue($version->results[0] instanceof Litmus_Result);
        $result = $version->results[0];

        $this->assertEquals($result->id, 1);
        $this->assertEquals($result->test_code, 'ie7');
        $this->assertEquals($result->state, 'pending');
    }
}
 

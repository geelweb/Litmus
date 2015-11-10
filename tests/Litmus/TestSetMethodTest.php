<?php
/**
 *
 *
 * @package Litmus_UnitTest
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 * @package Litmus_Test
 */

/**
 * Include test helper
 */
require_once __DIR__ . '/../TestHelper.php';

/**
 * Unit test class for Litmus Test Set Methods 
 * 
 * @package Litmus_UnitTest
 */
class Litmus_TestSetTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the tests RESTFul method
     *
     */
    public function testTestsMethod()
    {
        $tests = Litmus::getTests();
        $this->assertTrue(is_array($tests));
        $this->assertTrue($tests[0] instanceof Litmus_Test);
    }

    /**
     * Test the tests/show RESTFul method
     *
     */
    public function testTestsShowMethod()
    {
        // Get a test and check properties
        $test = Litmus::getTests(1);
        $this->assertTrue($test instanceof Litmus_Test);
        $this->assertEquals($test->id, 1);
        $this->assertEquals($test->name, 'Google');
        $this->assertEquals($test->service, 'page');
        $this->assertEquals($test->state, 'complete');
        $this->assertEquals($test->public_sharing, 'false');
        $this->assertEquals($test->url_or_guid, 'http://google.com');
        $this->assertTrue(is_array($test->test_set_versions));
        $this->assertTrue($test->test_set_versions[0] instanceof Litmus_Version);

        // try to get all the versions
        $versions = $test->getVersions();
        $this->assertTrue(is_array($versions));
        $this->assertTrue($versions[0] instanceof Litmus_Version);

        // get a version of the test and check properties
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

        // get a version of the test and check properties
        $result = $version->getResults(1);
        $this->assertTrue($result instanceof Litmus_Result);
        $this->assertEquals($result->check_state, null);
        $this->assertEquals($result->error_at, null);
        $this->assertEquals($result->finished_at, null);
        $this->assertEquals($result->id, 1);
        $this->assertEquals($result->started_at, null);
        $this->assertEquals($result->test_code, 'saf2');
        $this->assertEquals($result->state, 'pending');
        $this->assertEquals($result->result_type, 'page');
    }

    /**
     * Test the tests/destroy RESTFul method
     *
     */
    public function testTestsDestroyMethod()
    {
        $test = Litmus::getTests(1);
        $res = $test->destroy();
        $this->assertTrue($res);
    }

    /**
     * Test the tests/update RESTFul method
     *
     */
    public function testTestsUpdateMethod()
    {
        $test = Litmus::getTests(1);
        $updated_test = $test->update(array('name' => 'new Name'));
        $this->assertTrue($updated_test instanceof Litmus_Test);
        $this->assertEquals($updated_test->id, 1);
    }
}
 

<?php

namespace Geelweb\Litmus\Tests;

use Geelweb\Litmus\Litmus;
use Geelweb\Litmus\Version;
use Geelweb\Litmus\Result;

/**
 * Unit test class for Litmus Test Set Version Methods
 *
 * @package Litmus_UnitTest
 */
class TestSetVersionTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        Litmus::setAPICredentials(
            'geelweb', 'gluchet', 'xxxxxx',
            array(
                'enable_fake_server' => true,
            )
        );
    }

    /**
     * Test the versions RESTful method
     *
     */
    public function testVersionsMethod()
    {
        $test = Litmus::getTests(1);
        $versions = $test->getVersions();
        $this->assertTrue(is_array($versions));
        $this->assertTrue($versions[0] instanceof Version);
    }

    /**
     * Test the versions/show RESTful method
     *
     */
    public function testVersionsShowMethod()
    {
        $test = Litmus::getTests(1);
        $version = $test->getVersions(1);
        $this->assertTrue($version instanceof Version);
        $this->assertEquals($version->version, 1);
        $this->assertEquals($version->url_or_guid, 'http://google.com');
        $this->assertEquals($version->received, 'true');
        $this->assertTrue(is_array($version->results));
        $this->assertTrue($version->results[0] instanceof Result);

        // try to get all results of the version
        $results = $version->getResults();
        $this->assertTrue(is_array($results));
        $this->assertTrue($results[0] instanceof Result);
    }

    /**
     * Test the versions/create RESTful Method
     *
     */
    public function testVersionsCreateMethod()
    {
        $test = Litmus::getTests(1);
        $nb_versions = count($test->test_set_versions);
        $version = $test->createVersion();
        $this->assertEquals(count($test->test_set_versions), $nb_versions+1);
        $this->assertTrue($version instanceof Version);
    }

    /**
     * Test the versions/poll RESTful method
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
        $this->assertTrue($version->results[0] instanceof Result);
        $result = $version->results[0];

        $this->assertEquals($result->id, 1);
        $this->assertEquals($result->test_code, 'ie7');
        $this->assertEquals($result->state, 'pending');
    }
}


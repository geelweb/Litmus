<?php
/**
 * Litmus Test object
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

require_once 'Litmus/Test.php';

/**
 *
 */
abstract class Litmus_Test 
{
    private $_id;

    /**
     * Implements the tests and tests/show methods to get
     * all or one test. Return an array of Litmus_Test object or a single 
     * Litmus_Test object if a test_id is provide
     *
     * @param integer $test_id Id of the test to retrieve
     * @return mixed
     */
    public static function getTests($test_id=null)
    {
    }

    /**
     * Get the versions of the test
     *
     * @param integer $version_id Id of the version to retrieve
     * @return mixed
     */
    public function getVersions($version_id=null)
    {
    }

    /**
     * Implement the test/destroy method
     *
     * @return boolean
     */
    public function destroy()
    {
    }

    /**
     * Implement the test/update method
     *
     * @return boolean
     */
    public function update()
    {
    }

    abstract public function create();
    abstract public static function getClients();
}

<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

/**
 *
 */
class Litmus_Version 
{
    private $_id;

    /**
     * Implements the versions and versions/show methods to get all or one 
     * version of a test. Return an array of Litmus_Version objects or a
     * single Litmus_version object if a version_id is provide.
     *
     * @param integer $test_id Id of the test
     * @param integer $version_id Id of the version to retrieve
     * @return mixed
     */
    public static function getVersions($test_id, $version_id=null)
    {
    }

    /**
     * Get the test version result
     *
     * @param integer $result_id Id of the result to retrieve
     * @return mixed
     */
    public function getResults($result_id)
    {
    }

    public static function create($test_id)
    {
    }

    public function poll()
    {
    }
}
 

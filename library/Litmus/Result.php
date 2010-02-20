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
class Litmus_Result
{
    private $_id;

    /**
     * Implements the results and results/show methods to get all or one 
     * result of a version of a test. Return an array of Litmus_Result objects 
     * or a single Litmus_Result object if a version_id is provide.
     *
     * @param integer $test_id Id of the test
     * @param integer $version_id Id of the version
     * @param integer $result_id Id of the result to retrieve
     * @return mixed
     */
    public static function getResults($test_id, $version_id, $result_id=null)
    {
    }
}
 

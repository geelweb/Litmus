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

    public static function load($xml)
    {
        if ($xml instanceof DOMElement) {
            $dom = $xml;
        } else {
            $dom = DOMDocument::loadXML($xml);
        }
        $lst = $dom->getElementsByTagName('result');
        $col = array();
        foreach($lst as $item) {
            $obj = new Litmus_Result();
            foreach ($item->childNodes as $child) {
                $property = $child->tagName;
                $obj->$property = $child;
            }
            array_push($col, $obj);
        }
        return $col;
    }

    public function __set($property, $value)
    {
        switch($property) {
            case 'check_state':
            case 'error_at':
            case 'finished_at':
            case 'id':
            case 'started_at':
            case 'test_code':
            case 'state':
            case 'result_type':
                $this->$property = $value->nodeValue;
                break;
            case 'testing_application':
                break;
            case 'result_images':
                break;
        }
    }
}
 

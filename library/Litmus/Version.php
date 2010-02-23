<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

require_once 'Litmus/Result.php';

/**
 *
 */
class Litmus_Version 
{
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
        $rc = Litmus_RESTful_Client::singleton();
        if ($version_id === null) {
            $uri = 'tests/' . $test_id . '/versions.xml';
        } else {
            $uri = 'tests/' . $test_id . '/versions/' . $version_id . '.xml';
        }
        $res = $rc->get($uri);
        return Litmus_Test_Version::load($res);
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
        $rc = Litmus_RESTful_Client::singleton();
        $res = $rc->get('tests/' . $test_id . '/versions.xml');
    }

    public function poll()
    {
    }

    public static function load($xml)
    {
        if ($xml instanceof DOMElement) {
            $dom = $xml;
        } else {
            $dom = DOMDocument::loadXML($xml);
        }
        $lst = $dom->getElementsByTagName('test_set_version');
        $col = array();
        foreach($lst as $item) {
            $obj = new Litmus_Version();
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
            case 'version':
            case 'url_or_guid':
            case 'received':
                $this->$property = $value->nodeValue;
                break;
            case 'results':
                $this->$property = Litmus_Result::load($value);
                break;
        }
    }
}
 

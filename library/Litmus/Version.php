<?php
/**
 *
 * @package Litmus
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

/**
 *
 */
require_once __DIR__ . '/Result.php';

/**
 *
 * @package Litmus
 */
class Litmus_Version 
{
    private $_test_id = null;

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
        $versions = Litmus_Version::load($res, $test_id);
        if ($version_id !== null) {
            $versions = array_pop($versions);
        }
        return $versions;
    }

    /**
     * Get the test version result
     *
     * @param integer $result_id Id of the result to retrieve
     * @return mixed
     */
    public function getResults($result_id=null)
    {
        return Litmus_Result::getResults($this->getTestId(), $this->version, $result_id);
    }

    /**
     * Implements the versions/create method to create a new version of a test
     *
     * @param integer $test_id Id of the test
     * @return mixed
     */
    public static function create($test_id)
    {
        $rc = Litmus_RESTful_Client::singleton();
        $res = $rc->post('tests/' . $test_id . '/versions.xml');
        $versions = Litmus_Version::load($res, $test_id);
        return array_pop($versions);
    }

    /**
     * Implements the versions/poll method to get the state of a version
     */
    public function poll()
    {
        $rc = Litmus_RESTful_Client::singleton();
        $res = $rc->get(
            'tests/' . $this->getTestId() . '/versions/' . $this->version . '/poll.xml');
        $versions = Litmus_Version::load($res, $this->getTestId());
        return array_pop($versions);
    }

    public static function load($xml, $test_id)
    {
        if ($xml instanceof DOMElement) {
            $dom = $xml;
        } else {
            $dom = new DOMDocument();
            $dom->loadXML($xml);
        }
        $lst = $dom->getElementsByTagName('test_set_version');
        $col = array();
        foreach($lst as $item) {
            $obj = new Litmus_Version();
            $obj->setTestId($test_id);
            foreach ($item->childNodes as $child) {
                $property = $child->nodeName;
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

    public function setTestId($id)
    {
        $this->_test_id = $id;
    }

    public function getTestId()
    {
        return $this->_test_id;
    }
}
 

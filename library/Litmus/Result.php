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
require_once __DIR__ . '/Result/Testing/Application.php';
require_once __DIR__ . '/Result/Image.php';
require_once __DIR__ . '/Result/ResultHeader.php';

/**
 *
 * @package Litmus
 */
class Litmus_Result
{
    private $_version_id;
    private $_test_id;

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
        $rc = Litmus_RESTful_Client::singleton();
        if ($result_id === null) {
            $uri = 'tests/' . $test_id
                . '/versions/' . $version_id . '/results.xml';
        } else {
            $uri = 'tests/' . $test_id
                . '/versions/' . $version_id
                . '/results/' . $result_id . '.xml';
        }

        $res = $rc->get($uri);
        $results = Litmus_Result::load($res, $version_id, $test_id);
        if ($result_id !== null) {
            $results = array_pop($results);
        }
        return $results;
    }

    public static function load($xml, $version_id=null, $test_id=null)
    {
        if ($xml instanceof DOMElement) {
            $dom = $xml;
        } else {
            $dom = new DOMDocument();
            $dom->loadXML($xml);
        }
        $lst = $dom->getElementsByTagName('result');
        $col = array();
        foreach($lst as $item) {
            $obj = new Litmus_Result();
            foreach ($item->childNodes as $child) {
                $property = $child->nodeName;
                $obj->$property = $child;
                $obj->setVersionId($version_id);
                $obj->setTestId($test_id);
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
            case 'found_in_spam':
            case 'spam_score':
                $this->$property = $value->nodeValue;
                break;
            case 'testing_application':
                $this->$property = Litmus_Result_Testing_Application::load($value);
                break;
            case 'result_images':
                $this->$property = Litmus_Result_Image::load($value);
                break;
            case 'result_headers':
                $this->$property = Litmus_Result_ResultHeader::load($value);
                break;
        }
    }

    public function update($params)
    {
        $dom = new DOMDocument('1.0');
        $root = $dom->createElement('result');
        $dom->appendChild($root);

        // check state elm
        $ps = $dom->createElement(
            'check_state',
            isset($params['check_state']) ? $params['check_state'] : '');
        $root->appendChild($ps);

        $request = $dom->saveXML();
        $rc = Litmus_RESTful_Client::singleton();
        $res = $rc->put('tests/' . $this->getTestId()
            . '/versions/' . $this->getVersionId()
            . '/results/' . $this->id . '.xml', $request);
        $test = Litmus_Result::load($res);
        return array_pop($test);
    }

    /**
     * Implement the results/retest method
     *
     */
    public function retest()
    {
        $rc = Litmus_RESTful_Client::singleton();
        return $rc->post('tests/' . $this->getTestId()
            . '/versions/' . $this->getVersionId()
            . '/results/' . $this->id . '/retest.xml');
    }

    public function setVersionId($id)
    {
        $this->_version_id = $id;
    }

    public function getVersionId()
    {
        return $this->_version_id;
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


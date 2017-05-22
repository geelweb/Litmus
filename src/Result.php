<?php

namespace Geelweb\Litmus;

use Geelweb\Litmus\RESTful\Client;
use Geelweb\Litmus\Result\Testing\Application;
use Geelweb\Litmus\Result\Image;
use Geelweb\Litmus\Result\ResultHeader;

/**
 * Class Result
 * @package Geelweb\Litmus
 */
class Result
{
    /** @var int $_version_id */
    private $_version_id;
    /** @var int $_test_id */
    private $_test_id;

    /**
     * Implements the results and results/show methods to get all or one
     * result of a version of a test. Return an array of Litmus_Result objects
     * or a single Litmus_Result object if a version_id is provide.
     *
     * @param int $test_id Id of the test
     * @param int $version_id Id of the version
     * @param int $result_id Id of the result to retrieve
     * @return array|Result
     */
    public static function getResults($test_id, $version_id, $result_id=null)
    {
        $rc = Client::singleton();
        if ($result_id === null) {
            $uri = 'tests/' . $test_id
                . '/versions/' . $version_id . '/results.xml';
        } else {
            $uri = 'tests/' . $test_id
                . '/versions/' . $version_id
                . '/results/' . $result_id . '.xml';
        }

        $res = $rc->get($uri);
        $results = static::load($res, $version_id, $test_id);
        if ($result_id !== null) {
            $results = array_pop($results);
        }
        return $results;
    }

    /**
     * @param string $xml
     * @param int $version_id
     * @param int $test_id
     * @return array
     */
    public static function load($xml, $version_id = null, $test_id = null)
    {
        if ($xml instanceof \DOMElement) {
            $dom = $xml;
        } else {
            $dom = new \DOMDocument();
            $dom->loadXML($xml);
        }
        $lst = $dom->getElementsByTagName('result');
        $col = array();
        foreach($lst as $item) {
            $obj = new self;
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

    /**
     * @param string $property
     * @param mixed $value
     */
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
                $this->$property = Application::load($value);
                break;
            case 'result_images':
                $this->$property = Image::load($value);
                break;
            case 'result_headers':
                $this->$property = ResultHeader::load($value);
                break;
        }
    }

    /**
     * @param array $params
     * @return Result
     */
    public function update($params)
    {
        $dom = new \DOMDocument('1.0');
        $root = $dom->createElement('result');
        $dom->appendChild($root);

        // check state elm
        $ps = $dom->createElement(
            'check_state',
            isset($params['check_state']) ? $params['check_state'] : '');
        $root->appendChild($ps);

        $request = $dom->saveXML();
        $rc = Client::singleton();
        /** @noinspection PhpUndefinedFieldInspection */
        $res = $rc->put('tests/' . $this->getTestId()
            . '/versions/' . $this->getVersionId()
            . '/results/' . $this->id . '.xml', $request);
        $test = self::load($res);
        return array_pop($test);
    }

    /**
     * Implement the results/retest method
     *
     * @return string
     */
    public function retest()
    {
        $rc = Client::singleton();
        /** @noinspection PhpUndefinedFieldInspection */
        return $rc->post('tests/' . $this->getTestId()
            . '/versions/' . $this->getVersionId()
            . '/results/' . $this->id . '/retest.xml');
    }

    /** @param int $id */
    public function setVersionId($id)
    {
        $this->_version_id = $id;
    }

    /** @return int */
    public function getVersionId()
    {
        return $this->_version_id;
    }

    /** @param int $id */
    public function setTestId($id)
    {
        $this->_test_id = $id;
    }

    /** @return int */
    public function getTestId()
    {
        return $this->_test_id;
    }
}


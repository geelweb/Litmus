<?php

namespace Geelweb\Litmus;

use Geelweb\Litmus\RESTful\Client;

/**
 * Class Version
 * @package Geelweb\Litmus
 */
class Version
{
    private $_test_id = null;

    /**
     * Implements the versions and versions/show methods to get all or one
     * version of a test. Return an array of Litmus_Version objects or a
     * single Litmus_version object if a version_id is provide.
     *
     * @param int $test_id Id of the test
     * @param int $version_id Id of the version to retrieve
     * @return array|Version
     */
    public static function getVersions($test_id, $version_id = null)
    {
        $rc = Client::singleton();
        if ($version_id === null) {
            $uri = 'tests/' . $test_id . '/versions.xml';
        } else {
            $uri = 'tests/' . $test_id . '/versions/' . $version_id . '.xml';
        }
        $res = $rc->get($uri);
        $versions = self::load($res, $test_id);
        if ($version_id !== null) {
            $versions = array_pop($versions);
        }
        return $versions;
    }

    /**
     * Get the test version result
     *
     * @param int $result_id Id of the result to retrieve
     * @return array|Result
     */
    public function getResults($result_id = null)
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return Result::getResults($this->getTestId(), $this->version, $result_id);
    }

    /**
     * Implements the versions/create method to create a new version of a test
     *
     * @param int $test_id Id of the test
     * @return Version
     */
    public static function create($test_id)
    {
        $rc = Client::singleton();
        $res = $rc->post('tests/' . $test_id . '/versions.xml');
        $versions = self::load($res, $test_id);
        return array_pop($versions);
    }

    /**
     * Implements the versions/poll method to get the state of a version
     */
    public function poll()
    {
        $rc = Client::singleton();
        /** @noinspection PhpUndefinedFieldInspection */
        $res = $rc->get(
            'tests/' . $this->getTestId() . '/versions/' . $this->version . '/poll.xml');
        $versions = self::load($res, $this->getTestId());
        return array_pop($versions);
    }

    /**
     * @param string $xml
     * @param int $test_id
     * @return array
     */
    public static function load($xml, $test_id)
    {
        if ($xml instanceof \DOMElement) {
            $dom = $xml;
        } else {
            $dom = new \DOMDocument();
            $dom->loadXML($xml);
        }
        $lst = $dom->getElementsByTagName('test_set_version');
        $col = array();
        foreach($lst as $item) {
            $obj = new self;
            $obj->setTestId($test_id);
            foreach ($item->childNodes as $child) {
                $property = $child->nodeName;
                $obj->$property = $child;
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
            case 'version':
            case 'url_or_guid':
            case 'received':
                $this->$property = $value->nodeValue;
                break;
            case 'spam_seed_addresses':
                $spamSeedAddresses = array();
                $list = $value->getElementsByTagName('spam_seed_address');
                for ($i=0; $i<$list->length; $i++) {
                    /** @noinspection PhpUndefinedMethodInspection */
                    $spamSeedAddresses[] = $list->item($i)->nodeValue;
                }
                $this->$property = $spamSeedAddresses;
                break;
            case 'results':
                $this->$property = Result::load($value);
                break;
        }
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


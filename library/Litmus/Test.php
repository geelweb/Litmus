<?php
/**
 * Litmus Test object
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

require_once 'Litmus/Test.php';
require_once 'Litmus/Version.php';
require_once 'Litmus/Test/Client.php';

/**
 *
 */
class Litmus_Test 
{
    const TYPE_PAGE = 'pages';
    const TYPE_EMAIL = 'emails';

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
        $rc = Litmus_RESTful_Client::singleton();
        if ($test_id === null) {
            $res = $rc->get('tests.xml');
        } else {
            $res = $rc->get('tests/' . $test_id . '.xml');
        }

        return Litmus_Test::load($res);
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
        $rc = Litmus_RESTful_Client::singleton();
        return $rc->delete('tests/' . $this->id . '.xml');
    }

    /**
     * Implement the test/update method
     *
     * @return boolean
     */
    public function update()
    {
    }

    /**
     * Implemet the pages/create ane emails/create methods to ceate a new web 
     * page test or a new email test.
     *
     * @param string $test_type Type of the test (TYPE_PAGE||TYPE_EMAIL)
     * @param array $params Parameters of the test
     * @return Litmus_Test
     */
    public static function create($test_type, $params)
    {
        $dom = new DomDocument('1.0', 'UTF-8');
        $root = $dom->createElement('test_set');
        $dom->appendChild($root);

        // application element
        $applications = $dom ->createElement('applications');
        $type = $dom->createAttribute('type');
        $type->appendChild($dom->createTextNode('array'));
        $applications->appendChild($type);
        $root->appendChild($applications);

        if (isset($params['applications'])) {
            foreach ($params['applications'] as $app) {
                $app_node = $dom->createElement('application');
                $code_node = $dom->createElement('code', $app);
                $app_node->appendChild($code_node);
                $applications->appendChild($app_node);
            }
        }

        // url node
        $node = $dom->createElement('url', $params['url']);
        $root->appendChild($node);

        // save defaults node
        $node = $dom->createElement(
            'save_defaults',
            (isset($params['save_defaults']) && $params['save_defaults']) ? 'true' : 'false');
        $root->appendChild($node);

        // use defaults node
        $node = $dom->createElement(
            'use_defaults',
            (isset($params['use_defaults']) && $params['use_defaults']) ? 'true' : 'false');
        $root->appendChild($node);

        $request = $dom->saveXML();

        $rc = Litmus_RESTful_Client::singleton();
        $res = $rc->post($test_type . '.xml', $request);
        
        $test = Litmus_Test::load($res);
        return $test;
    }

    /**
     * Implement the pages/clients and emails/clients methods to get the 
     * available clients for web pages and email tests
     *
     * @param string the type of clients to retrieve (TYPE_PAGE|TYPE_EMAIL)
     * @return array
     */
    public static function getClients($type)
    {
        $rc = Litmus_RESTful_Client::singleton();
        $res = $rc->get($type . '/clients.xml');
        return Litmus_Test_Client::load($res);
    }

    /**
     * Load a Litmus_Test object or collection from an xml content.
     *
     * @param string $xml XML content
     * @return mixed
     */
    public static function load($xml)
    {
        $dom = DOMDocument::loadXML($xml);
        $lst = $dom->getElementsByTagName('test_set');
        $col = array();
        foreach ($lst as $item) {
            $obj = new Litmus_Test();
            foreach ($item->childNodes as $child) {
                $property = $child->tagName;
                $obj->$property = $child;
            }
            array_push($col, $obj);
        }
        return count($col)==1 ? $obj : $col;
    }

    /**
     * Set the Litmus_Test property from XML DOMElement
     *
     * @param string $property the property to set
     * @param mixed $value the Value or DOMElement
     * @return void
     */
    public function __set($property, $value)
    {
        switch($property) {
            case 'created_at':
            case 'id':
            case 'updated_at':
            case 'name':
            case 'service':
            case 'state':
            case 'public_sharing':
                $this->$property = $value->nodeValue;
                break;
            case 'test_set_versions':
                $this->$property = Litmus_Version::load($value);
                break;
        }
    }
}
<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

require_once 'Litmus/Test.php';
require_once 'Litmus/Version.php';
require_once 'Litmus/Test/Client/Page.php';

/**
 *
 */
class Litmus_Test_Page extends Litmus_Test 
{
    //public $created_at;
    //public $id;
    //public $updated_at;
    //public $name;
    //public $service;
    //public $state;
    //public $public_sharing;
    //public $test_set_versions;

    /**
     * Implement the pages/create methode
     *
     * @return Litmus_Test_Page
     */
    public static function create($params)
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
        $res = $rc->post('pages.xml', $request);
        $test = Litmus_Test_Page::load($res);
        return $test;
    }

    /**
     * Implement the pages/clients methode and return an array of
     * Litmus_Test_Client_Page objects
     *
     * @return array
     */
    public static function getClients()
    {
        $rc = Litmus_RESTful_Client::singleton();
        $res = $rc->get('pages/clients.xml');
        
        $dom = DOMDocument::loadXML($res);
        $elms = $dom->getElementsByTagName('testing_application');
        $col = array();
        foreach($elms as $elm) {
            $o = new Litmus_Test_Client_Page($elm);
            array_push($col, $o);
        }
        return $col;
    }

    public static function load($xml)
    {
        $obj = new Litmus_Test_Page();
        $dom = DOMDocument::loadXML($xml);
        $lst = $dom->getElementsByTagName('test_set');
        $root = $lst->item(0);
        foreach ($root->childNodes as $child) {
            $property = $child->tagName;
            $obj->$property = $child;
        }
        return $obj;
    }

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
  

<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

require_once 'Litmus/Test.php';
require_once 'Litmus/Test/Client/Page.php';

/**
 *
 */
class Litmus_Test_Page extends Litmus_Test 
{
    /**
     * Implement the pages/create methode
     *
     */
    public function create()
    {
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
}
  

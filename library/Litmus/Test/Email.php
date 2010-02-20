<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

require_once 'Litmus/Test.php';
require_once 'Litmus/Test/Client/Email.php';

/**
 *
 */
class Litmus_Test_Email extends Litmus_Test
{
    /**
     * Implement the emails/create methode
     *
     */
    public function create()
    {
    }

    /**
     * Implement the emails/clients methode and return an array of
     * Litmus_Test_Client_Email objects
     *
     * @return array
     */
    public static function getClients()
    {
        $rc = Litmus_RESTful_Client::singleton();
        $res = $rc->get('emails/clients.xml');
        
        $dom = DOMDocument::loadXML($res);
        $elms = $dom->getElementsByTagName('testing_application');
        $col = array();
        foreach($elms as $elm) {
            $o = new Litmus_Test_Client_Email($elm);
            array_push($col, $o);
        }
        return $col;

    }
}
 

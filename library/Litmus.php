<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

require_once 'Litmus/RESTful/Client.php';
require_once 'Litmus/Test/Page.php';
require_once 'Litmus/Test/Email.php';

/**
 *
 */
class Litmus
{
    /**
     * Initialize the RESTful Client with the API credentials
     *
     * @return void
     */
    public static function setAPICredentials($key, $username, $password, $opt=array())
    {
        $rc = Litmus_RESTful_Client::singleton();
        $rc->setCredentials($key, $username, $password, $opt);
    }

    /**
     * Get the availables page clients
     *
     * @return array
     */
    public static function getPageClients()
    {
        return Litmus_Test_Page::getClients();
    }

    /**
     * Get the availables email clients
     *
     * @return array
     */
    public static function getEmailClients()
    {
        return Litmus_Test_Email::getClients();
    }

    public function createPageTest($params)
    {
        return Litmus_Test_Page::create($params);
    }

    public function createEmailTest()
    {
    }
}
 

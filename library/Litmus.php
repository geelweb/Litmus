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
require_once __DIR__ . '/Litmus/RESTful/Client.php';
require_once __DIR__ . '/Litmus/Test.php';

/**
 *
 * @package Litmus
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
        return Litmus_Test::getClients(Litmus_Test::TYPE_PAGE);
    }

    /**
     * Get the availables email clients
     *
     * @return array
     */
    public static function getEmailClients()
    {
        return Litmus_Test::getClients(Litmus_Test::TYPE_EMAIL);
    }

    /**
     * Create a new web page test
     *
     * @param array $params
     * @return Litmus_Test
     */
    public static function createPageTest($params)
    {
        return Litmus_Test::create(Litmus_Test::TYPE_PAGE, $params);
    }

    /**
     * Create a new email test
     *
     * @param array $params
     * @return Litmus_Test
     */
    public static function createEmailTest($params)
    {
        $tests = Litmus_Test::create(Litmus_Test::TYPE_EMAIL, $params);
        return array_pop($tests);
    }

    /**
     * Return the list of the available tests or a single test if a test id
     * is provide
     *
     * @return mixed
     */
    public static function getTests($test_id=null)
    {
        return Litmus_Test::getTests($test_id);
    }
}


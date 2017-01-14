<?php

namespace Geelweb\Litmus;

use Geelweb\Litmus\RESTful\Client;
use Geelweb\Litmus\Test;

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
        $rc = Client::singleton();
        $rc->setCredentials($key, $username, $password, $opt);
    }

    /**
     * Get the availables page clients
     *
     * @return array
     */
    public static function getPageClients()
    {
        return Test::getClients(Test::TYPE_PAGE);
    }

    /**
     * Get the availables email clients
     *
     * @return array
     */
    public static function getEmailClients()
    {
        return Test::getClients(Test::TYPE_EMAIL);
    }

    /**
     * Create a new web page test
     *
     * @param array $params
     * @return Litmus_Test
     */
    public static function createPageTest($params)
    {
        return Test::create(Test::TYPE_PAGE, $params);
    }

    /**
     * Create a new email test
     *
     * @param array $params
     * @return Litmus_Test
     */
    public static function createEmailTest($params)
    {
        $tests = Test::create(Test::TYPE_EMAIL, $params);
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
        return Test::getTests($test_id);
    }
}


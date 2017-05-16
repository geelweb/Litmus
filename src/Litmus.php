<?php

namespace Geelweb\Litmus;

use Geelweb\Litmus\RESTful\Client;

/**
 * Class Litmus
 * @package Geelweb\Litmus
 */
class Litmus
{
    /**
     * Initialize the RESTful Client with the API credentials
     * @param string $key
     * @param string $username
     * @param string $password
     * @param array $opt
     */
    public static function setAPICredentials($key, $username, $password, $opt = array())
    {
        $rc = Client::singleton();
        $rc->setCredentials($key, $username, $password, $opt);
    }

    /**
     * Get the available page clients
     *
     * @return array
     */
    public static function getPageClients()
    {
        return Test::getClients(Test::TYPE_PAGE);
    }

    /**
     * Get the available email clients
     *
     * @return array
     */
    public static function getEmailClients()
    {
        return Test::getClients(Test::TYPE_EMAIL);
    }

    /**
     * Create a new web page test
     * @param array $params
     * @return array
     */
    public static function createPageTest($params)
    {
        return Test::create(Test::TYPE_PAGE, $params);
    }

    /**
     * Create a new email test
     * @param array $params
     * @return Test
     */
    public static function createEmailTest($params)
    {
        $tests = Test::create(Test::TYPE_EMAIL, $params);
        return array_pop($tests);
    }

    /**
     * Return the list of the available tests or a single test if a test id
     * is provide
     * @param int $test_id
     * @return array|Test
     */
    public static function getTests($test_id = null)
    {
        return Test::getTests($test_id);
    }
}


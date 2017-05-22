<?php

namespace Geelweb\Litmus\RESTful;
use Exception;

/**
 * Class Client
 * @package Geelweb\Litmus\RESTful
 */
class Client
{
    private static $_instance = null;

    /** @var string $_api_key */
    private $_api_key = null;
    /** @var string $_api_username */
    private $_api_username = null;
    /** @var string _api_password */
    private $_api_password = null;

    private $_curl_handle = null;

    private $_curl_result = null;
    private $_curl_error = null;
    private $_curl_info = null;

    private $_fse = false;
    /** @var Server $_server */
    private $_server = null;

    /** Constructor */
    private function __construct()
    {
    }

    /**
     * Singleton : return the current instance of the Client
     *
     * @return Client
     */
    public static function singleton()
    {
        if (self::$_instance===null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Set the Litmus API credentials
     *
     * @param $key string
     * @param $username string
     * @param $password string
     * @param array $opts array
     * @return void
     */
    public function setCredentials($key, $username, $password, $opts = array())
    {
        $this->_api_key = $key;
        $this->_api_username = $username;
        $this->_api_password = $password;

        if (isset($opts['enable_fake_server']) && $opts['enable_fake_server']) {
            $this->_fse = true;
            $this->_server = new Server();
        }
    }

    /**
     * Build the REST url
     *
     * @param $uri string
     * @return string
     */
    private function _getApiUrl($uri)
    {
        return 'https://' . $this->_api_key . '.litmus.com/' . $uri;
    }

    /**
     * Init the curl session
     *
     * @param $uri string
     * @return void
     */
    private function _initCurlSession($uri)
    {
        $this->_curl_handle = curl_init();
        curl_setopt($this->_curl_handle, CURLOPT_URL, $this->_getApiUrl($uri));
        curl_setopt($this->_curl_handle, CURLOPT_USERPWD,
            sprintf('%s:%s', $this->_api_username, $this->_api_password));
        curl_setopt($this->_curl_handle, CURLOPT_RETURNTRANSFER, 1);
    }

    /**
     * Perform the curl session
     * @return void
     * @throws Exception
     */
    private function _performCurlSession()
    {
        $this->_curl_result  = curl_exec($this->_curl_handle);
        $this->_curl_error = curl_error($this->_curl_handle);
        $this->_curl_info = curl_getinfo($this->_curl_handle);

        curl_close($this->_curl_handle);

        if (preg_match('/^[3|4|5][0-9]{2}$/', $this->_curl_info['http_code'])) {
            throw new Exception(sprintf(
                'An error occurs calling %s : %s (http_code %s) : %s',
                $this->_curl_info['url'],
                $this->_curl_error,
                $this->_curl_info['http_code'],
                (string) $this->_curl_result));
        }
    }

    /**
     * Perform a fake session calling the Server to get a fake
     * result.
     *
     * @param $method string
     * @param $uri string
     * @param null $request
     * @return void
     */
    private function _performFakeSession($method, $uri, $request = null)
    {
        $this->_curl_result  = $this->_server->perform($method, $uri, $request);
        $this->_curl_error = $this->_server->error;
        $this->_curl_info = $this->_server->info;
    }

    /**
     * Get the result of the last performed curl session
     *
     * @return string
     */
    public function getLastCurlResult()
    {
        return $this->_curl_result;
    }

    /**
     * Get the error of the last performed curl session
     *
     * @return string
     */
    public function getLastCurlError()
    {
        return $this->_curl_error;
    }

    /**
     * Get the information about the last performed curl session
     *
     * @return string
     */
    public function getLastCurlInfo()
    {
        return $this->_curl_info;
    }

    /**
     * Perform a curl GET request
     *
     * @param $uri string
     * @return string
     */
    public function get($uri)
    {
        if ($this->_fse) {
            $this->_performFakeSession('GET', $uri);
            return $this->_curl_result;
        }
        $this->_initCurlSession($uri);
        $this->_performCurlSession();
        return $this->_curl_result;
    }

    /**
     * Perform a curl POST request
     *
     * @param $uri string
     * @param $request string
     * @return string
     */
    public function post($uri, $request = null)
    {
        if ($this->_fse) {
            $this->_performFakeSession('POST', $uri, $request);
            if (!empty($this->_curl_info)) {
                return $this->_curl_info['http_code'] == '201';
            } else {
                return $this->_curl_result;
            }
        }
        $this->_initCurlSession($uri);
        curl_setopt($this->_curl_handle, CURLOPT_POST, true);
        $headers = array(
            'Content-Type: application/xml',
        );
        curl_setopt($this->_curl_handle, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($this->_curl_handle, CURLOPT_VERBOSE, true);

        if ($request === null) {
            $request = array();
        }
        curl_setopt($this->_curl_handle, CURLOPT_POSTFIELDS, $request);

        $this->_performCurlSession();
        return $this->_curl_result;
    }

    /**
     * Perform a curl PUT request
     *
     * @param $uri string
     * @param $request string
     * @return string
     */
    public function put($uri, $request = null)
    {
        if ($this->_fse) {
            $this->_performFakeSession('PUT', $uri, $request);
            return $this->_curl_result;
        }
        $this->_initCurlSession($uri);
        curl_setopt($this->_curl_handle, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($request !== null) {
            curl_setopt($this->_curl_handle, CURLOPT_POSTFIELDS, $request);
        }
        $this->_performCurlSession();
        return $this->_curl_result;
    }

    /**
     * Perform a curl DELETE request
     *
     * @param $uri string
     * @return string
     */
    public function delete($uri)
    {
        if ($this->_fse) {
            $this->_performFakeSession('DELETE', $uri);
            return $this->_curl_info['http_code'] == '200';
        }
        $this->_initCurlSession($uri);
        curl_setopt($this->_curl_handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->_performCurlSession();
        return $this->_curl_info['http_code'] == '200';
    }
}


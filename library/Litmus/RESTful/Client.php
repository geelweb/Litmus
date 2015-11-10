<?php
/**
 * Litmus RESTful client
 *
 * @package Litmus_RESTful
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

/**
 *
 */
require_once __DIR__ . '/Server.php';

/**
 *
 * @package Litmus_RESTful
 */
class Litmus_RESTful_Client {
    private static $_instance = null;

    private $_api_key = null;
    private $_api_username = null;
    private $_api_password = null;

    private $_curl_handle = null;

    private $_curl_result = null;
    private $_curl_error = null;
    private $_curl_info = null;

    private $_fse = false;
    private $_server = null;

    /**
     * Constructor
     *
     * @return void
     */
    private function __construct()
    {
    }

    /**
     * Singleton : return the current instance of the Litmus_RESTful_Client
     *
     * @return Litmus_RESTful_Client
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
     * @return void
     */
    public function setCredentials($key, $username, $password, $opts = array())
    {
        $this->_api_key = $key;
        $this->_api_username = $username;
        $this->_api_password = $password;

        if (isset($opts['enable_fake_server']) && $opts['enable_fake_server']) {
            $this->_fse = true;
            $this->_server = new Litmus_RESTful_Server();
        }
    }

    /**
     * Build the REST url
     *
     * @return string
     */
    private function _getApiUrl($uri)
    {
        return 'https://' . $this->_api_key . '.litmus.com/' . $uri;
    }

    /**
     * Init the curl session
     *
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
     *
     * @return void
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
     * Perform a fake session calling the Litmus_RESTful_Server to get a fake
     * result.
     *
     * @return void
     */
    private function _performFakeSession($method, $uri, $request=null)
    {
        $this->_curl_result  = $this->_server->perform($method, $uri, $request);
        $this->_curl_error = $this->_server->error;
        $this->_curl_info = $this->_server->info;
    }

    /**
     * Get the result of the last performed curl session
     *
     * @return mixed
     */
    public function getLastCurlResult()
    {
        return $this->_curl_result;
    }

    /**
     * Get the error of the last performed curl session
     *
     * @return mixed
     */
    public function getLastCurlError()
    {
        return $this->_curl_error;
    }

    /**
     * Get the infor,ations about the last performed curl session
     *
     * @return mixed
     */
    public function getLastCurlInfo()
    {
        return $this->_curl_info;
    }

    /**
     * Perform a curl GET request
     *
     * @return mixed
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
     * @return mixed
     */
    public function post($uri, $request=null)
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
            $request = [];
        }
        curl_setopt($this->_curl_handle, CURLOPT_POSTFIELDS, $request);

        $this->_performCurlSession();
        return $this->_curl_result;
    }

    /**
     * Perform a curl PUT request
     *
     * @return mixed
     */
    public function put($uri, $request=null)
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
     * @return mixed
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


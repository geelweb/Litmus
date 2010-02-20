<?php
/**
 * Litmus RESTful client
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

/**
 *
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
     * @return Litmus_RESTful8Client
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
    }

    /**
     * Build the REST url
     * 
     * @return string
     */
    private function _getApiUrl($uri)
    {
        return 'http://' . $this->_api_key . '.litmusapp.com/' . $uri;
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
        $this->_initCurlSession($uri);
        curl_setopt($this->_curl_handle, CURLOPT_POST, true);
        if ($request !== null) {
            curl_setopt($this->_curl_handle, CURLOPT_POSTFIELDS, $request);
        }
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
        $this->_initCurlSession($uri);
        curl_setopt($this->_curl_handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->_performCurlSession();
        return $this->_curl_result;
    }
}


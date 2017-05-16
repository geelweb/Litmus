<?php

namespace Geelweb\Litmus\RESTful;

/**
 * Class Server
 * @package Geelweb\Litmus\RESTful
 */
class Server
{
    public $result;
    public $error;
    public $info;

    private function _init()
    {
        $this->result = null;
        $this->error = null;
        $this->info = null;
    }

    /**
     * @param $method string
     * @param $uri string
     * @param $request string
     * @return bool|string
     */
    public function perform($method, $uri, $request = null)
    {
        unset($request);
        $this->_init();
//        echo 'METHOD : ', $method, "\n";
//        echo 'URI : ', $uri, "\n";
        if ($method == 'GET' || $method == 'PUT') {
            if (preg_match('/tests\/\d+.xml/', $uri)) {
                $this->result = file_get_contents(dirname(__FILE__) . '/Server/tests/1.xml');
                return $this->result;
            }
            $file = dirname(__FILE__) . '/Server/' . $uri;
            if (file_exists($file)) {
                $this->result = file_get_contents($file);
                return $this->result;
            }
        } elseif ($method == 'POST') {
            if (preg_match('/tests\/\d+\/versions.xml/', $uri)) {
                $this->result = file_get_contents(dirname(__FILE__) . '/Server/tests/1/versions/1.xml');
                return $this->result;
            }
            if (preg_match('/tests\/\d+\/versions\/\d+\/results\/\d+\/retest.xml/', $uri)) {
                $this->info = array('http_code' => '201');
                $this->result = 'Created';
                return $this->result;
            }
            $file = dirname(__FILE__) . '/Server/' . $uri;
            if (file_exists($file)) {
                $this->result = file_get_contents($file);
                return $this->result;
            }
        } elseif ($method == 'DELETE') {
            if(preg_match('/tests\/\d+\.xml/', $uri)) {
                $this->info = array('http_code' => '200');
                $this->result = 'OK';
                return $this->result;
            }
        }
        return false;
    }
}

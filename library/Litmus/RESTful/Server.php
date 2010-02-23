<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

class Litmus_RESTful_Server {
    public $result;
    public $error;
    public $info;

    public function perform($method, $uri, $request=null)
    {
        $file = dirname(__FILE__) . '/Server/' . $uri;
        if (file_exists($file)) {
            $this->result = file_get_contents($file);
            return $this->result;
        } 

        if ($method == 'GET' || $method == 'PUT') {
            if(preg_match('/tests\/\d\.xml/', $uri)) {
                $this->result = file_get_contents(dirname(__FILE__) . '/tests/1.xml');
                return $this->result;
            }
        } elseif ($method == 'POST') {
        } elseif ($method == 'DELETE') {
            if(preg_match('/tests\/\d+\.xml/', $uri)) {
                $this->info = array('http_code' => '200');
                $this->result = 'OK';
                return $this->result;
            }
        }
        
        
    }
}


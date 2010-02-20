<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

class Litmus_RESTful_Server {
    public function __construct()
    {
    }

    public function perform($uri, $request=null)
    {
        $file = dirname(__FILE__) . '/Server/' . $uri;
        if ($request===null && file_exists($file)) {
            return file_get_contents($file);
        } elseif($request!==null && file_exists($file)) {
            return file_get_contents($file);
        }
    }
}


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
 * @package Litmus
 */
class Litmus_Result_Testing_Application
{
    public static function load($xml)
    {
        if ($xml instanceof DOMElement) {
            $dom = $xml;
        } else {
            $dom = new DOMDocument();
            $dom->loadXML($xml);
        }
        $obj = new Litmus_Result_Testing_Application();
        foreach ($dom->childNodes as $child) {
            $property = $child->nodeName;
            $obj->$property = $child;
        }
        return $obj;
    }

    public function __set($property, $value)
    {
        switch ($property) {
            case 'average_time_to_process':
            case 'result_type':
            case 'popular':
            case 'status':
            case 'platform_name':
            case 'platform_long_name':
            case 'application_code':
            case 'application_long_name':
                $this->$property = $value->nodeValue;
                break;
        }
    }
}

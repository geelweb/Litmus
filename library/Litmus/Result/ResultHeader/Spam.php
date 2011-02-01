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
class Litmus_Result_ResultHeader_Spam
{
    public static function load($xml)
    {
        if ($xml instanceof DOMElement) {
            $dom = $xml;
        } else {
            $dom = new DOMDocument();
            $dom->loadXML($xml);
        }
        $obj = new Litmus_Result_ResultHeader_Spam();
        foreach ($dom->childNodes as $child) {
            $property = $child->nodeName;
            $obj->$property = $child;
        }
        return $obj;
    }

    public function __set($property, $value)
    {
        switch ($property) {
            case 'description':
            case 'rating':
            case 'key':
                $this->$property = $value->nodeValue;
                break;
        }
    }
}

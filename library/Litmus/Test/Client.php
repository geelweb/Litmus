<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

class Litmus_Test_Client
{
    public static function load($xml)
    {
        $dom = DOMDocument::loadXML($xml);
        $lst = $dom->getElementsByTagName('testing_application');
        $col = array();
        foreach($lst as $item) {
            $obj = new Litmus_Test_Client();
            foreach ($item->childNodes as $child) {
                $property = $child->tagName;
                $obj->$property = $child;
            }
            array_push($col, $obj);
        }
        return $col;
    }

    public function __set($property, $value)
    {
        switch ($property) {
            default:
                $this->$property = $value->nodeValue; 
        }
    }
}
 

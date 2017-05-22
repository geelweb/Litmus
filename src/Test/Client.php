<?php

namespace Geelweb\Litmus\Test;

/**
 * Class Client
 * @package Geelweb\Litmus\Test
 */
class Client
{
    /**
     * @param string $xml
     * @return array
     */
    public static function load($xml)
    {
        $dom = new \DOMDocument;
        $dom->loadXML($xml);
        $lst = $dom->getElementsByTagName('testing_application');
        $col = array();
        foreach($lst as $item) {
            $obj = new self;
            foreach ($item->childNodes as $child) {
                $property = $child->nodeName;
                $obj->$property = $child;
            }
            array_push($col, $obj);
        }
        return $col;
    }

    /**
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        switch ($property) {
            default:
                $this->$property = $value->nodeValue;
        }
    }
}


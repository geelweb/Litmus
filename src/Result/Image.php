<?php

namespace Geelweb\Litmus\Result;

/**
 * Class Image
 * @package Geelweb\Litmus\Result
 */
class Image
{
    /**
     * @param string $xml
     * @return array
     */
    public static function load($xml)
    {
        if ($xml instanceof \DOMElement) {
            $dom = $xml;
        } else {
            $dom = new \DOMDocument();
            $dom->loadXML($xml);
        }
        $lst = $dom->getElementsByTagName('result_image');
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
            case 'image_type':
            case 'full_image':
            case 'thumbnail_image':
                $this->$property = $value->nodeValue;
                break;
        }
    }
}

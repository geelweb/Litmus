<?php

namespace Geelweb\Litmus\Result\ResultHeader;

/**
 * Class Spam
 * @package Geelweb\Litmus\Result\ResultHeader
 */
class Spam
{
    /**
     * @param string $xml
     * @return Spam
     */
    public static function load($xml)
    {
        if ($xml instanceof \DOMElement) {
            $dom = $xml;
        } else {
            $dom = new \DOMDocument();
            $dom->loadXML($xml);
        }
        $obj = new self;
        foreach ($dom->childNodes as $child) {
            $property = $child->nodeName;
            $obj->$property = $child;
        }
        return $obj;
    }

    /**
     * @param string $property
     * @param mixed $value
     */
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

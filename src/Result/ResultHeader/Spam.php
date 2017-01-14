<?php

namespace Geelweb\Litmus\Result\ResultHeader;

class Spam
{
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

<?php

namespace Geelweb\Litmus\Result;

use Geelweb\Litmus\Result\ResultHeader\Spam;

/**
 * Class ResultHeader
 * @package Geelweb\Litmus\Result
 */
class ResultHeader
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
        $lst = $dom->getElementsByTagName('result_header');
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
            case 'spam_header':
                $this->$property = Spam::load($value);
                break;
        }
    }
}

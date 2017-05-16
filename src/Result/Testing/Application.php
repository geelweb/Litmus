<?php

namespace Geelweb\Litmus\Result\Testing;

/**
 * Class Application
 * @package Geelweb\Litmus\Result\Testing
 */
class Application
{
    /**
     * @param string $xml
     * @return Application
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

<?php
/**
 *
 * @package Litmus
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

require_once __DIR__ . '/ResultHeader/Spam.php';

/**
 *
 * @package Litmus
 */
class Litmus_Result_ResultHeader
{
    public static function load($xml)
    {
        if ($xml instanceof DOMElement) {
            $dom = $xml;
        } else {
            $dom = new DOMDocument();
            $dom->loadXML($xml);
        }
        $lst = $dom->getElementsByTagName('result_header');
        $col = array();
        foreach($lst as $item) {
            $obj = new Litmus_Result_ResultHeader();
            foreach ($item->childNodes as $child) {
                $property = $child->nodeName;
                $obj->$property = $child;
            }
            array_push($col, $obj);
        }
        return $col;

    }

    public function __set($property, $value)
    {
        switch ($property) {
            case 'spam_header':
                $this->$property = Litmus_Result_ResultHeader_Spam::load($value);
                break;
        }
    }
}

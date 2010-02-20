<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

abstract class Litmus_Test_Client extends ArrayObject
{
    protected $_properties = array();

    public function __construct($elm)
    {
        foreach ($this->_properties as $tag => $v) {
            $list = $elm->getElementsByTagName($tag);
            $this->_properties[$tag] = $list->item(0)->nodeValue;
        }
    }

    public function __get($property)
    {
        if (isset($this->_properties[$property])) {
            return $this->_properties[$property];
        }
        return null;
    }
}
 

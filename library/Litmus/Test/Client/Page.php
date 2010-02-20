<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

require_once 'Litmus/Test/Client.php';

/**
 *
 */
class Litmus_Test_Client_Page extends Litmus_Test_Client
{
    protected $_properties = array(
        'average_time_to_process' => null,
        'result_type' => null,
        'popular' => null,
        'status' => null,
        'default' => null,
        'platform_name' => null,
        'platform_long_name' => null,
        'application_code' => null,
        'application_long_name' => null,
    );
}
 

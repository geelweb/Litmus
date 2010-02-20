<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

require_once 'Litmus/Test/Client.php';

class Litmus_Test_Client_Email extends Litmus_Test_Client
{
    protected $_properties = array(
        'average_time_to_process' => null,
        'business' => null,
        'result_type' => null,
        'supports_content_blocking' => null,
        'desktop_client' => null,
        'status' => null,
        'default' => null,
        'platform_name' => null,
        'platform_long_name' => null,
        'application_code' => null,
        'application_long_name' => null,
    );


}
 

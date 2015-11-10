<?php
/**
 *
 * @package Litmus_UnitTest
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

ob_start();

error_reporting(E_ALL | E_STRICT);

ini_set('display_errors', 'on');
ini_set('memory_limit', -1);

define('BASE_PATH', realpath(dirname(__FILE__)) . '/..');

set_include_path(
    BASE_PATH . '/library' . PATH_SEPARATOR
    . BASE_PATH . '/tests' . PATH_SEPARATOR
    . get_include_path());

require_once __DIR__ . '/../library/Litmus.php';
Litmus::setAPICredentials(
    'geelweb', 'gluchet', 'xxxxxx',
    array(
        'enable_fake_server' => true,
    ));

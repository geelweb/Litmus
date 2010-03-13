<?php

set_include_path(
    dirname(__FILE__) . '/../library' . PATH_SEPARATOR
    . get_include_path()
);

require_once 'Litmus.php';

try {
Litmus::setAPICredentials('geelweb', 'gluchet', 'vw89ycy1', array(
    'enable_fake_server' => true));
$tests = Litmus::getTests();
print_r($tests);
exit;

// get pages clients
//$clients = Litmus::getPageClients();
//foreach ($clients as $cli) {
//    print_r($cli);
//}
// get emails clients
//$clients = Litmus::getEmailClients();
//foreach ($clients as $cli) {
//    print_r($cli);
//}
echo "CREATE A PAGE TEST\n";
$pages = Litmus::createPageTest(array(
    'applications' => array(
        'saf2', 'ie7'
    ),
    'url' => 'http://geelweb.org',
    'save_defaults' => false,
    'use_defaults' => false));
print_r($pages);
echo "UPDQTE TEST\n";
$pages->update(array('name'=> 'My new test name'));
echo "DESTROY THE PAGE TEST\n";
$res = $pages->destroy();
echo $res ? "TEST DESTROYED\n" : "ERROR DESTROYING THE TEST\n";
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}

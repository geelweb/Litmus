# Litmus PHP library

PHP Implementation of the [Litmus](http://litmusapp.com) Customer RESTful API
to test email and web pages on many email clients and browsers.

## Install

 * Download the zip file or checkout the source
 * Put it somewhere in your path
 * Use it

## Examples

Set Litmus API credentials:

    Litmus::setAPICredentials(
        'your_api_key',
        'your_api_credential',
        'your_api_password');

Get the email clients availables for tests:

    $clients = Litmus::getEmailClients();

Get the clients availables to test web-pages:

    $clients = Litmus::getPageClients();

Create a page test on Safari 2 and IE7:

    Litmus::createPageTest(array(
        'applications' => array('saf2', 'ie7'),
        'url' => 'http://geelweb.org',
    ));

[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=gluchet&url=https://github.com/geelweb/Litmus&title=Litmus&language=&tags=github&category=software)

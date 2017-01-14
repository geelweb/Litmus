# Litmus PHP library

[![Build Status](https://travis-ci.org/geelweb/Litmus.svg?branch=master)](https://travis-ci.org/geelweb/Litmus)

PHP Implementation of the [Litmus](http://litmusapp.com) Customer RESTful API
to test email and web pages on many email clients and browsers.

## Install

    composer require geelweb/litmus

## Examples

Set Litmus API credentials:

    use Geelweb\Litmus\Litmus;

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


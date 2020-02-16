<?php
/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */
require dirname(__DIR__) . '/vendor/autoload.php';

define('TESTING', true);

require dirname(__DIR__) . '/config/bootstrap.php';


$_SERVER['PHP_SELF'] = '/';

<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
 ob_start();
chdir(dirname(__DIR__));

error_reporting(E_ALL);ob_start();
// Setup autoloading
include 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(include 'config/application.config.php')->run();

define('REQUEST_MICROTIME', microtime(true));






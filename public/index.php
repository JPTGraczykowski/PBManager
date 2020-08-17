<?php

/**
 * Front controller
 *
<<<<<<< HEAD
 * PHP version 7.0
=======
 * PHP version 5.4
>>>>>>> 40af4e4a56966a0ccb88f2ec1ebf37dd27385227
 */

/**
 * Composer
 */
<<<<<<< HEAD
require dirname(__DIR__) . '/vendor/autoload.php';
=======
require '../vendor/autoload.php';


/**
 * Twig
 */
Twig_Autoloader::register();
>>>>>>> 40af4e4a56966a0ccb88f2ec1ebf37dd27385227


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
<<<<<<< HEAD
=======
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
>>>>>>> 40af4e4a56966a0ccb88f2ec1ebf37dd27385227
    
$router->dispatch($_SERVER['QUERY_STRING']);

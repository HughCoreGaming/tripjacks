<?php
// Define path to application directory
defined('APPLICATION_PATH')
 || define('APPLICATION_PATH', ($_SERVER['DOCUMENT_ROOT'] . '/../application'));
 
// Define application environment
defined('APPLICATION_ENV')
 || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
 
set_include_path(implode(PATH_SEPARATOR, array(
 realpath(APPLICATION_PATH . '/../library/TripJacks'),
 get_include_path(),
)));
 
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
// Register folder library/Mylib/ for custom classes
$autoloader->registerNamespace('Trip_');
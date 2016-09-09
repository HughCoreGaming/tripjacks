<?php
// include main Doctrine class file
include_once 'C:\xampp\php\Doctrine\Doctrine.php';
spl_autoload_register(array('Doctrine', 'autoload'));

// create Doctrine manager
$manager = Doctrine_Manager::getInstance();

// create database connection

$conn = Doctrine_Manager::connection('mysql://root@localhost/tripjacks', 'doctrine');

//auto-generate models
Doctrine::generateModelsFromDb('C:\xampp\htdocs\tripjacks\temp\models', array('doctrine'), array('classPrefix' => 'Tripjacks_Model_'));
?>

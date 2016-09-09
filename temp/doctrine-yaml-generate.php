<?php
// include main Doctrine class file
include_once 'C:\xampp\php\Doctrine\Doctrine.php';
spl_autoload_register(array('Doctrine', 'autoload'));


//auto-generate models
Doctrine_Core::generateYamlFromDb('C:\xampp\htdocs\tripjacks\temp\models');
?>


        


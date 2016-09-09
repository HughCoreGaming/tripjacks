<?php
require_once('bootstrap.php'); // Setup Zend Framework Environment
header("Cache-Control: no-cache");
$rating = new Tripjacks_Model_Player;
$score = $rating->test(); // rate object and get scores
echo Zend_Json::encode($score); // send response array in JSON format
?>
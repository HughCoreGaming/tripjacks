<?php

header("Cache-Control: no-cache");
$rating = new Trip_Model_Player;
$score = $rating->test(); // rate object and get scores
echo Zend_Json::encode($score); // send response array in JSON format
?>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tripjacks_Security_Solutions
 *
 * @author Hugh Templeton
 */
class Tripjacks_Security_Solutions {
                         
     
     public function sql_secure($value) {
        $value = strip_tags($value);
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        } //check if the mysql_real_escape_string function exists
        if (function_exists("mysql_real_escape_string")) {
            $value = mysql_real_escape_string($value);
        } //for PHP version prior to 4.3.0 use addslashes
        else {
            $value = addslashes($value);
        }
        return $value;
    }

}


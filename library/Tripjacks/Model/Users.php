<?php

/**
 * Tripjacks_Model_Users
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Tripjacks_Model_Users extends Tripjacks_Model_BaseUsers
{
    
    public function setUp() {
    
    }

// Salt Generator
 public function generate_salt()
{ 
     // Declare $salt
     $salt = '';

     // And create it with random num
     for ($i = 0; $i < 4; $i++)
     { 
          $salt .= rand(0,9); 
     } 
          return $salt;
}



// Salt2 Generator
 public function generate_salt2()
{ 
     // Declare $salt
     $salt = '';

     // And create it with random num
     for ($i = 0; $i < 8; $i++)
     { 
          $salt .= rand(0,9); 
     } 
          return $salt;
}

//random letter generator

public function randLetter()
{
    $int = rand(0,25);
    $a_z = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $rand_letter = $a_z[$int];
    return $rand_letter;
}

public function getUserid($username) {
        $q = Doctrine_Query::create()        
                ->from('Tripjacks_Model_Users u')
                ->where('u.username = ?', $username);
                
        $result = $q->fetchArray();

        return $result[0];
 }
 


public function player_memberno_exists($memberno) {
        $q = Doctrine_Query::create()        
                ->from('Tripjacks_Model_Player p')
                ->where('p.memberno = ?', $memberno);
                
        $result = count($q->fetchArray());

        return $result;
 }
 
 public function venue_memberno_exists($memberno) {
        $q = Doctrine_Query::create()        
                ->from('Tripjacks_Model_Venue v')
                ->where('v.memberno = ?', $memberno);
                
        $result = count($q->fetchArray());

        return $result;
 }
 


}
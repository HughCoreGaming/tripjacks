<?php

/**
 * Tripjacks_Model_League
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Tripjacks_Model_League extends Tripjacks_Model_BaseLeague
{
    
   public function getLeagues() {
    $q = Doctrine_Query::create()
          ->from('Tripjacks_Model_League l')
          ->orderBy('l.leagueid ASC');
    $result = $q->fetchArray();
    
    return $result;

  }
  
  

}
<?php

/**
 * Tripjacks_Model_Main
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Tripjacks_Model_Main extends Tripjacks_Model_BaseMain
{
    
        public function getMain() {
        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_Main m')
                ->orderby('m.mainid DESC')
                ->limit(1);

        $result = $q->fetchArray();


        return $result[0];
    }

}
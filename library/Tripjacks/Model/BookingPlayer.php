<?php

/**
 * Tripjacks_Model_BookingPlayer
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Tripjacks_Model_BookingPlayer extends Tripjacks_Model_BaseBookingPlayer {

    public function setUp() {

        $this->hasOne('Tripjacks_Model_BookingGame', array(
            'local' => 'booking_gameid',
            'foreign' => 'booking_gameid'
        ));
        
        $this->hasOne('Tripjacks_Model_Player', array(
            'local' => 'playerid',
            'foreign' => 'playerid'
        ));



    }
    


    public function countPlaying($bgid) {
        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_BookingPlayer bp')
                ->where('bp.booking_gameid = ?', $bgid);
        $result = count($q->fetchArray());

        return $result;
    }
    

    
        public function playeridfromPlaying($bgid) {
       $q = Doctrine_Query::create()
                ->select('playerid as pid')
                ->from('Tripjacks_Model_BookingPlayer bp')
                ->where('bp.booking_gameid = ?', $bgid);
        $result = $q->fetchArray();
        return $result;
    }
    
     public function playerinfo($pid) {
       $q = Doctrine_Query::create()
               ->select('player as name, propic as pic, playerid as pid')
                ->from('Tripjacks_Model_Player p')
                ->where('p.playerid = ?', $pid);
        $result = $q->fetchArray();
        return $result;
    }
    
    
        public function getPlayerid($uid) {
        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_Player p')
                ->where('p.userid = ?', $uid);
        $result = $q->fetchArray();
        return $result[0];
    }
    


    public function getName($bgid) {
        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_BookingPlayer bp')
                ->where('bp.booking_gameid = ?', $bgid);
        $result = $q->fetchArray();
        return $result;
    }
    
    
        public function countPlayer($pid,$bgid) {
        $q = Doctrine_Query::create()
                ->select('COUNT(playerid) as pid')
                ->from('Tripjacks_Model_BookingPlayer bp')
                ->where('bp.playerid = ?', $pid)
                ->addWhere('bp.booking_gameid = ?', $bgid);
        $result = $q->fetchArray();
        return $result[0]['pid'];
    }
    

    public function getPaid($bgid) {
        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_BookingGame bg')
                ->where('bg.booking_gameid = ?', $bgid);
        $result = $q->fetchArray();
        return $result[0]['paid'];
    }
    
        public function getType($bgid) {
        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_BookingGame bg')
                ->where('bg.booking_gameid = ?', $bgid);
        $result = $q->fetchArray();
        return $result[0]['type'];
    }

}
<?php

/**
 * Tripjacks_Model_Results
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Tripjacks_Model_Results extends Tripjacks_Model_BaseResults {

    public function setUp() {
        $this->hasMany('Tripjacks_Model_Games', array(
            'local' => 'gameid',
            'foreign' => 'gameid'
                )
        );
        
        $this->hasOne('Tripjacks_Model_Attendance', array(
            'local' => 'attendanceid',
            'foreign' => 'attendanceid'
                )
        );
        $this->hasMany('Tripjacks_Model_Player', array(
            'local' => 'playerid',
            'foreign' => 'playerid'
                )
        );
    }
    
    //All
        public function countAllPlayed($pid) {
        
     
        
        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.playerid = ?', $pid);
        $result = count($q->fetchArray());
        
        return $result;
    
    }
    
    public function countAllAvg($pid) {
        
  

        $q = Doctrine_Query::create()
                ->addSelect('ROUND(AVG(r.score),0) AS score')
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.playerid = ?', $pid);
           
         $result = $q->fetchArray();
        
        return $result[0]['score'];
    }


    
        public function countAllScore($pid) {



        $q = Doctrine_Query::create()
                ->addSelect('SUM(score) AS score')
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.playerid = ?', $pid);

        $result = $q->fetchArray();

        return $result[0]['score'];
    }
    
    
    //Winter

    public function countWinterPlayed($attendanceid) {
        
                $arr = array("Winter", date('Y'));
        $season = join(" ", $arr);
        
        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.attendanceid = ?', $attendanceid)
                ->addWhere('g.season = ?', $season);
        $result = count($q->fetchArray());
        
        return $result;
    
    }
    
    public function countWinterAvg($attendanceid) {
        
                $arr = array("Winter", date('Y'));
        $season = join(" ", $arr);

        $q = Doctrine_Query::create()
                ->addSelect('ROUND(AVG(r.score),0) AS score')
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.attendanceid = ?', $attendanceid)
                ->addWhere('g.season = ?', $season);
           
         $result = $q->fetchArray();
        
        return $result[0]['score'];
    }


    
        public function countWinterScore($attendanceid) {

        $arr = array("Winter", date('Y'));
        $season = join(" ", $arr);

        $q = Doctrine_Query::create()
                ->addSelect('SUM(score) AS score')
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.attendanceid = ?', $attendanceid)
                ->addWhere('g.season = ?', $season);

        $result = $q->fetchArray();
       

        return $result[0]['score'];
    }
    
    
      
    //spring

    public function countSpringPlayed($attendanceid) {
        
                $arr = array("Spring", date('Y'));
        $season = join(" ", $arr);
        
        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.attendanceid = ?', $attendanceid)
                ->addWhere('g.season = ?', $season);
        $result = count($q->fetchArray());
        
        return $result;
    
    }
    
    public function countSpringAvg($attendanceid) {
        
                $arr = array("Spring", date('Y'));
        $season = join(" ", $arr);

        $q = Doctrine_Query::create()
                ->addSelect('ROUND(AVG(r.score),0) AS score')
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.attendanceid = ?', $attendanceid)
                ->addWhere('g.season = ?', $season);
           
         $result = $q->fetchArray();
        
        return $result[0]['score'];
    }


    
        public function countSpringScore($attendanceid) {

        $arr = array("Spring", date('Y'));
        $season = join(" ", $arr);

        $q = Doctrine_Query::create()
                ->addSelect('SUM(score) AS score')
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.attendanceid = ?', $attendanceid)
                ->addWhere('g.season = ?', $season);

        $result = $q->fetchArray();

        return $result[0]['score'];
    }
    
    
    
    //Summer

    public function countSummerPlayed($attendanceid) {
        
                $arr = array("Summer", date('Y'));
        $season = join(" ", $arr);
        
        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.attendanceid = ?', $attendanceid)
                ->addWhere('g.season = ?', $season);
        $result = count($q->fetchArray());
        
        return $result;
    
    }
    
    public function countSummerAvg($attendanceid) {
        
                $arr = array("Summer", date('Y'));
        $season = join(" ", $arr);

        $q = Doctrine_Query::create()
                ->addSelect('ROUND(AVG(r.score),0) AS score')
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.attendanceid = ?', $attendanceid)
                ->addWhere('g.season = ?', $season);
           
         $result = $q->fetchArray();
        
        return $result[0]['score'];
    }


    
        public function countSummerScore($attendanceid) {

        $arr = array("Summer", date('Y'));
        $season = join(" ", $arr);

        $q = Doctrine_Query::create()
                ->addSelect('SUM(score) AS score')
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.attendanceid = ?', $attendanceid)
                ->addWhere('g.season = ?', $season);

        $result = $q->fetchArray();

        return $result[0]['score'];
    }
    
    
    
    //Autumn

    public function countAutumnPlayed($attendanceid) {
        
                $arr = array("Autumn", date('Y'));
        $season = join(" ", $arr);
        
        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.attendanceid = ?', $attendanceid)
                ->addWhere('g.season = ?', $season);
        $result = count($q->fetchArray());
        
        return $result;
    
    }
    
    public function countAutumnAvg($attendanceid) {
        
                $arr = array("Autumn", date('Y'));
        $season = join(" ", $arr);

        $q = Doctrine_Query::create()
                ->addSelect('ROUND(AVG(r.score),0) AS score')
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.attendanceid = ?', $attendanceid)
                ->addWhere('g.season = ?', $season);
           
         $result = $q->fetchArray();
        
        return $result[0]['score'];
    }


    
        public function countAutumnScore($attendanceid) {

        $arr = array("Autumn", date('Y'));
        $season = join(" ", $arr);

        $q = Doctrine_Query::create()
                ->addSelect('SUM(score) AS score')
                ->from('Tripjacks_Model_Results r')
                ->innerJoin('r.Tripjacks_Model_Games g')
                ->where('r.attendanceid = ?', $attendanceid)
                ->addWhere('g.season = ?', $season);

        $result = $q->fetchArray();

        return $result[0]['score'];
    }


}

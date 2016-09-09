<?php
class Leagues_LeagueController extends Zend_Controller_Action
{  
  public function init() 
  {
    $this->view->doctype('XHTML1_STRICT');
    $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/center.css');
    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabletab.js');
  }

  // action to display a cms news
  public function displayAction()
  {
    $q = Doctrine_Query::create()
          ->from('Tripjacks_Model_Venue v')
            ->where('v.type = ?', 'normal');
    $result = $q->fetchArray();
    $this->view->venue = $result; 
  }
  
      // action to display list of cms news
  public function mainleaguesAction()
  {
  // set filters and validators for GET input
    $filters = array(
      'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
    );    
    $validators = array(
      'id' => array('NotEmpty', 'Int')
    );
    $input = new Zend_Filter_Input($filters, $validators);
    $input->setData($this->getRequest()->getParams());

    // test if input is valid
    // retrieve requested record
    // attach to view
    if ($input->isValid()) {
      $q = Doctrine_Query::create()
            ->from('Tripjacks_Model_SubLeague sl')
            ->where('sl.leagueid = ?', $input->id);
      $result = $q->fetchArray();

        $this->view->league = $result;               
    
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }
  
  
  
      // action to display list of catalog items
    public function indexAction() {
        // set filters and validators for GET input
        $filters = array(
            'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'id' => array('NotEmpty', 'Int')
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());

        // test if input is valid
        // retrieve requested record
        // attach to view
        if ($input->isValid()) {

            
            $league_players = new Tripjacks_Model_Player;
            $results = new Tripjacks_Model_Results;
            $attendances = new Tripjacks_Model_Attendance;


            $player_info = $league_players->getLeaguePlayers($input->id);
            
            //counts
            $countgames = $league_players->countGames($input->id);
            $countplayers = $league_players->countLeaguePlayers($input->id);
            
            //find pass percent for red line
            
            $per = $countplayers * 25 / 100;
            $per2 = round($per);
            $section = 1;


            $this->view->countgames = $countgames;
            $this->view->percent = $per2;
            $this->view->section = $section;
            
       
            $Winterarr = array();
            $Springarr = array();
            $Summerarr = array();
            $Autumnarr = array();
            
//Winter league
            
 

            foreach ($player_info as $value) {

                $playerid = $value['playerid'];
                $player = $value['player'];
                $memberno = $value['memberno'];
                $attendanceid = $attendances->getAttendanceid($input->id, $playerid);

                $venueplayed = $results->countWinterPlayed($attendanceid);
                $venueavg = $results->countWinterAvg($attendanceid);
                $venuetotal = $results->countWinterScore($attendanceid);

                if ($venueavg == "") {
                    $venueavg = 0;
                }

                if ($venuetotal == "") {
                    $venuetotal = 0;
                }


                $Winterarr[] = array($venuetotal, $memberno, $venueplayed, $venueavg, $player, $playerid);
            }

            sort($Winterarr);
            $Winterarr2 = array_reverse($Winterarr);


            $this->view->Winterleague = $Winterarr2;
            
            
            
            //Spring league

            foreach ($player_info as $value) {

                $playerid = $value['playerid'];
                $player = $value['player'];
                $memberno = $value['memberno'];
                $attendanceid = $attendances->getAttendanceid($input->id, $playerid);

                $venueplayed = $results->countSpringPlayed($attendanceid);
                $venueavg = $results->countSpringAvg($attendanceid);
                $venuetotal = $results->countSpringScore($attendanceid);

                if ($venueavg == "") {
                    $venueavg = 0;
                }

                if ($venuetotal == "") {
                    $venuetotal = 0;
                }


                $Springarr[] = array($venuetotal, $memberno, $venueplayed, $venueavg, $player, $playerid);
            }

            sort($Springarr);
            $Springarr2 = array_reverse($Springarr);


            $this->view->Springleague = $Springarr2;
            
            
            //Summer league

            foreach ($player_info as $value) {

                $playerid = $value['playerid'];
                $player = $value['player'];
                $memberno = $value['memberno'];
                $attendanceid = $attendances->getAttendanceid($input->id, $playerid);

                $venueplayed = $results->countSummerPlayed($attendanceid);
                $venueavg = $results->countSummerAvg($attendanceid);
                $venuetotal = $results->countSummerScore($attendanceid);

                if ($venueavg == "") {
                    $venueavg = 0;
                }

                if ($venuetotal == "") {
                    $venuetotal = 0;
                }


                $Summerarr[] = array($venuetotal, $memberno, $venueplayed, $venueavg, $player, $playerid);
            }

            sort($Summerarr);
            $Summerarr2 = array_reverse($Summerarr);


            $this->view->Summerleague = $Summerarr2;
            
            //Autumn league

            foreach ($player_info as $value) {

                $playerid = $value['playerid'];
                $player = $value['player'];
                $memberno = $value['memberno'];
                $attendanceid = $attendances->getAttendanceid($input->id, $playerid);

                $venueplayed = $results->countAutumnPlayed($attendanceid);
                $venueavg = $results->countAutumnAvg($attendanceid);
                $venuetotal = $results->countAutumnScore($attendanceid);

                if ($venueavg == "") {
                    $venueavg = 0;
                }

                if ($venuetotal == "") {
                    $venuetotal = 0;
                }


                $Autumnarr[] = array($venuetotal, $memberno, $venueplayed, $venueavg, $player, $playerid);
            }

            sort($Autumnarr);
            $Autumnarr2 = array_reverse($Autumnarr);


            $this->view->Autumnleague = $Autumnarr2;
            
            
            
            
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }
    
    
          // action to display list of catalog items
    public function specialindexAction() {
        // set filters and validators for GET input
        $filters = array(
            'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'id' => array('NotEmpty', 'Int')
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());

        // test if input is valid
        // retrieve requested record
        // attach to view
        if ($input->isValid()) {
            
                $the_auth = Zend_Auth::getInstance()->getIdentity();
                $userid = $the_auth[0]["userid"];
                $access = $the_auth[0]["access"];
                
                //Check venue is autherised befor buy game. Compare auth adapter with get param
                
                $alreadyin = 'No';
                
                $player_info = new Tripjacks_Model_Player;
                $playerid = $player['playerid'];
                $player = $player_info->getPlayerid($userid);
                
                if ($access == 'player'){
            
                $count = $player_info->countSpecialLeaguePlayers($input->id,$playerid);

                if ($count > 0){
                    $alreadyin = 'Yes';
                }
                }

            
            $league_players = new Tripjacks_Model_Player;
            $results = new Tripjacks_Model_Results;
            $subleague = new Tripjacks_Model_SubLeague;
      

            $player_info = $league_players->getSpecialLeaguePlayers($input->id);
            $subleague_info = $subleague->getSubLeaguesDetails($input->id);
          
            
            //Is player the right sex
            
      
            if ($player['sex']==$subleague_info[0]['sex']||$subleague_info[0]['sex']=="either"){
                $sex_access = "ok";
            }else{
                $sex_access = "denied";
            }
            
            //counts
         
            $countplayers = count($player_info);
            
            //find pass percent for red line
            
            $per = $countplayers * 25 / 100;
            $per2 = round($per);
            $section = 1;
            
            $this->view->sex_access = $sex_access;   
            $this->view->access = $access;              
            $this->view->alreadyin = $alreadyin;
            $this->view->subleague = $subleague_info;
            $this->view->percent = $per2;
            $this->view->section = $section;
            $this->view->playerid = $playerid;
            
       
            $arr = array();
      


            foreach ($player_info as $value) {

                $playerid = $value['playerid'];
                $more_player_info = $league_players->getPlayer($playerid);

                $venueplayed = $results->countAllPlayed($playerid);
                $venueavg = $results->countAllAvg($playerid);
                $venuetotal = $results->countAllScore($playerid);

                if ($venueavg == "") {
                    $venueavg = 0;
                }

                if ($venuetotal == "") {
                    $venuetotal = 0;
                }


                $arr[] = array($venuetotal, $more_player_info[0]['memberno'], $venueplayed, $venueavg, $more_player_info[0]['player'], $playerid);
            }

            sort($arr);
            $arr2 = array_reverse($arr);


            $this->view->league = $arr2;
            
            
            
            
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }
    
    
    public function createAction()
  {
    // generate input form
    $form = new Tripjacks_Form_NewsCreate;
    $this->view->form = $form;
    
    // test for valid input
    // if valid, populate model
    // assign default values for some fields
    // save to database
    if ($this->getRequest()->isPost()) {
      if ($form->isValid($this->getRequest()->getPost())) {
        $news = new Tripjacks_Model_News;
        $news->fromArray($form->getValues());      
        $news->save();
        $id = $news->newsid;  
        $this->_helper->getHelper('FlashMessenger')->addMessage('Your submission has been accepted as news #' . $id . '. A moderator will review it and, if approved, it will appear on the site within 48 hours.');
        $this->_redirect('/cms/news/success');
      }   
    } 
  }


  public function successAction()
  {
    if ($this->_helper->getHelper('FlashMessenger')->getMessages()) {
      $this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();    
    } else {
      $this->_redirect('/');    
    } 
  }  
  
}
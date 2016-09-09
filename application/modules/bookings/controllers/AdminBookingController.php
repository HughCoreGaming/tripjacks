<?php

class Bookings_AdminBookingController extends Zend_Controller_Action {

    public function init() {
        $this->view->doctype('XHTML1_STRICT');
        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/css/center.css');
    }

    // action to handle admin URLs
    public function preDispatch() {
        // set admin layout
        // check if user is authenticated
        // if not, redirect to login page
        $url = $this->getRequest()->getRequestUri();
        $this->_helper->layout->setLayout('admin');
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $session = new Zend_Session_Namespace('tripjacks.auth');
            $session->requestURL = $url;
            $this->_redirect('/admin/login');
        }
    }
  
 
      public function searchAction() {

        //get the q parameter from URL
        $data = $this->_request->getPost();

        $q = $data["q"];
        $option = $data["option"];

        if ($option == "username") {

            $playerfunctions = new Tripjacks_Model_Player;
            $result = $playerfunctions->getListofUsername();

            $a = array();
            foreach ($result as $r) {
                array_push($a, $r['username']);
            }

            //lookup all hints from array if length of q>0
            if (strlen($q) > 0) {
                $hint = "";
                for ($i = 0; $i < count($a); $i++) {
                    if (strtolower($q) == strtolower(substr($a[$i], 0, strlen($q)))) {
                        if ($hint == "") {
                            $hint = $a[$i];
                        }
                    }
                }
            }

            // Set output to "no suggestion" if no hint were found
            // or to the correct values
            if ($hint == "") {
                $response = "no suggestion";
            } else {
                $response = $hint;
            }
            //output the response

            echo Zend_Json::encode($response);
            exit;
        } else {

            $playerfunctions = new Tripjacks_Model_Player;
            $result = $playerfunctions->getListofMemberno();

            $a = array();
            foreach ($result as $r) {
                array_push($a, $r['memberno']);
            }

            //lookup all hints from array if length of q>0
            if (strlen($q) > 0) {
                $hint = "";
                for ($i = 0; $i < count($a); $i++) {
                    if (strtolower($q) == strtolower(substr($a[$i], 0, strlen($q)))) {
                        if ($hint == "") {
                            $hint = $a[$i];
                        }
                    }
                }
            }

            // Set output to "no suggestion" if no hint were found
            // or to the correct values
            if ($hint == "") {
                $response = "no suggestion";
            } else {
                $response = $hint;
            }

            //output the response

            echo Zend_Json::encode($response);
            exit;
        }
    }

    // action to display list of venues venue
    public function gameindexAction() {
        // set filters and validators for GET input
        $filters = array(
            'venueid' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'venueid' => array('NotEmpty', 'Int')
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());

        // test if input is valid
        // retrieve requested record
        // attach to view
        if ($input->isValid()) {

            $game_players = new Tripjacks_Model_BookingPlayer;
            $game_dates = new Tripjacks_Model_BookingGame;
            $venuegames = $game_dates->getvenueGameDate($input->venueid);
            $skirmishgames = $game_dates->getskirmishGameDate($input->venueid);

            if (Zend_Auth::getInstance()->hasIdentity()) {
                $hasid = 1;
            } else {
                $hasid = 0;
            }

            $this->view->venueid = $input->venueid;
            $this->view->hasid = $hasid;
            $this->view->records = $venuegames;
            $this->view->records2 = $skirmishgames;
            $this->view->game_players = $game_players;
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }

    public function cancelbookingAction() {

        // set filters and validators for GET input
        $filters = array(
            'bookingid' => array('HtmlEntities', 'StripTags', 'StringTrim'),
            'venueid' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'bookingid' => array('NotEmpty', 'Int'),
            'venueid' => array('NotEmpty', 'Int')
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());
        if ($input->isValid()) {

            $the_auth = Zend_Auth::getInstance()->getIdentity();
            $userid = $the_auth[0]["userid"];
 

            $venue_info = new Tripjacks_Model_Player;
            $temp_playerid = $venue_info->getPlayerInfoWithoutUsers($userid);

            $q = Doctrine_Query::create()
                    ->delete('Tripjacks_Model_BookingPlayer bp')
                    ->where('bp.booking_gameid = ?', $input->bookingid)
                    ->addWhere('bp.playerid = ?', $temp_playerid[0]['playerid']);
            $result = $q->execute();

            $this->_redirect('/admin/bookings/booking/bookingindex/' . $input->bookingid . '/' . $input->venueid);
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }
    
        public function venuecancelbookingAction() {

        // set filters and validators for GET input
        $filters = array(
            'bookingid' => array('HtmlEntities', 'StripTags', 'StringTrim'),
            'venueid' => array('HtmlEntities', 'StripTags', 'StringTrim'),
            'playerid' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'bookingid' => array('NotEmpty', 'Int'),
            'venueid' => array('NotEmpty', 'Int'),
            'playerid' => array('NotEmpty', 'Int')
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());
        if ($input->isValid()) {



            $q = Doctrine_Query::create()
                    ->delete('Tripjacks_Model_BookingPlayer bp')
                    ->where('bp.booking_gameid = ?', $input->bookingid)
                    ->addWhere('bp.playerid = ?', $input->playerid);
            $result = $q->execute();

            $this->_redirect('/admin/bookings/booking/bookingindex/' . $input->bookingid . '/' . $input->venueid);
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }
    
        public function insertAction() {

        $data = $this->_request->getPost();
        
        $users = new Tripjacks_Model_Users;
        $players = new Tripjacks_Model_Player;
        
        $bookingid = $data['bookingid'];
        $venueid =$data['venueid'];
        $user = $data['user'];
        $option = $data["option"];

        if ($user != "no suggestion"){
            
            if ($option == "username"){
                $userid = $users->getUserid($user);
            }else{
                $userid = $players->getUserid2($user);
            }

           $booking_players = new Tripjacks_Model_BookingPlayer;
           $count = $booking_players->countPlaying($bookingid);

 
            if ($count == NULL) {
                $count = 0;
            }
            
            $slots = 16;
            $slots_avail = $slots - $count;
            $slot_place = $count + 1;
            $player_info = $booking_players->getPlayerid($userid['userid']);
            $playerid = $player_info['playerid'];
            $count_player = $booking_players->countPlayer($playerid, $bookingid);
            $player = $player_info['player'];

            if ($count_player == 0) {

                $booking_player = new Tripjacks_Model_BookingPlayer;
                $booking_player->playerid = $playerid;
                $booking_player->name = $player;
                $booking_player->slot = $slot_place;
                $booking_player->booking_gameid = $bookingid;
                $booking_playerid = $booking_player->save();
            }
        }

        exit;
    }
        
    public function skirmishsetupAction() {

        $values = $this->_request->getPost();
        $newdate = $values['date'];

        $booking_game = new Tripjacks_Model_BookingGame;
        $count = $booking_game->dateExists($values['venueid'], $newdate);

        if ($values['date'] == "") {
            echo Zend_Json::encode('Please enter date first!');
            exit;
        } elseif ($count > 0) {
            echo Zend_Json::encode('There is already a game on this date!');
            exit;
        }
        
        $venue = new Tripjacks_Model_Venue;
        $newDateUK = $values['date'];
        $year = substr($newDateUK, 6, 10);
        $month = substr($newDateUK, 3, 2);

        $season = $venue->get_season($month, $year);
        $played = "No";
        $paid = "Yes";
        $type = "skirmish";

        $booking_game->date = $newdate;
        $booking_game->season = $season;
        $booking_game->played = $played;
        $booking_game->paid = $paid;
        $booking_game->type = $type;
        $booking_game->venueid = $values['venueid'];
        $booking_game->save();

        echo Zend_Json::encode('New Skimish game added!');
        exit;
    }

    public function changetimesAction() {
         $values = $this->_request->getPost();
                $venue = Doctrine::getTable('Tripjacks_Model_Venue')->find($values['venueid']);     
                $venue->start = $values['time'];
                $venue->save();
                
                echo Zend_Json::encode('New Skimish game added!');
                exit;
    }   

    // action to display list of venues venue
    public function bookingindexAction() {
        // set filters and validators for GET input
        $filters = array(
            'bookingid' => array('HtmlEntities', 'StripTags', 'StringTrim'),
            'venueid' => array('HtmlEntities', 'StripTags', 'StringTrim')
      
        );
        $validators = array(
            'bookingid' => array('NotEmpty', 'Int'),
            'venueid' => array('NotEmpty', 'Int')
          
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());
        if ($input->isValid()) {

            $booking_players = new Tripjacks_Model_BookingPlayer;
            $booking_game = new Tripjacks_Model_BookingGame;

            $count = $booking_players->countPlaying($input->bookingid);
            $names = $booking_players->getName($input->bookingid);
            $paid = $booking_players->getPaid($input->bookingid);
            $get_date = $booking_game->get_date($input->bookingid);
 
            if ($count == NULL) {
                $count = 0;
            }
            $slots = 16;
            $slots_avail = $slots - $count;
            $slot_place = $count + 1;
            
            
                $the_auth = Zend_Auth::getInstance()->getIdentity();
                $userid = $the_auth[0]["userid"];
                $access = $the_auth[0]["access"];
                
                //Check venue is autherised befor buy game. Compare auth adapter with get param
                
                $auth = 'no';
                
                if ($access == 'venue'){
                $venue_info = new Tripjacks_Model_Player;
                $venueid = $venue_info->getVenueid($userid);
                if ($venueid == $input->venueid){
                    $auth = 'yes';
                }
                }
                
            //check to show cancel
                
                $venue_info = new Tripjacks_Model_Player;
                $bp_info = new Tripjacks_Model_BookingPlayer;
                $temp_playerid = $venue_info->getPlayerInfoWithoutUsers($userid);
                $type = $bp_info->getType($input->bookingid);
      
            $this->view->type = $type;
            $this->view->bookingid = $input->bookingid;
            $this->view->names = $names;
            $this->view->slot_place = $slot_place;
            $this->view->slot_place_other = $slot_place;
            $this->view->paid = $paid;
            $this->view->access = $access;              
            $this->view->auth = $auth;
            $this->view->venueid = $input->venueid;
            $this->view->date = date("d/m/Y", strtotime($get_date));
            
            if($access == "player"){
            $this->view->temp_playerid = $temp_playerid[0]['playerid'];
            }   
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }
    
    
    
  // action to delete cms news
  public function deleteAction()
  {
    // set filters and validators for POST input
    $filters = array(
      'ids' => array('HtmlEntities', 'StripTags', 'StringTrim')
    );    
    $validators = array(
      'ids' => array('NotEmpty', 'Int')
    );
    $input = new Zend_Filter_Input($filters, $validators);
    $input->setData($this->getRequest()->getParams());
    
    // test if input is valid
    // read array of record identifiers
    // delete records from database
    if ($input->isValid()) {
      $q = Doctrine_Query::create()
            ->delete('Tripjacks_Model_News n')
            ->whereIn('n.newsid', $input->ids);
      $result = $q->execute();               
      $this->_helper->getHelper('FlashMessenger')->addMessage('The records were successfully deleted.');
      $this->_redirect('/admin/cms/news/success');
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }
  
  
  
    // action to modify an individual cms news
    public function buyupdateAction() {

        // set filters and validators for GET input
        $filters = array(
            'bookingid' => array('HtmlEntities', 'StripTags', 'StringTrim'),
            'venueid' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'bookingid' => array('NotEmpty', 'Int'),
            'venueid' => array('NotEmpty', 'Int')
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());

        // test if input is valid
        // read array of record identifiers
        // delete records from database
        if ($input->isValid()) {


            $the_auth = Zend_Auth::getInstance()->getIdentity();
            $userid = $the_auth[0]["userid"];
            $access = $the_auth[0]["access"];

            $auth = 'no';

            if ($access == 'venue') {
                $venue_info = new Tripjacks_Model_Player;
                $venueid = $venue_info->getVenueid($userid);
                if ($venueid == $input->venueid) {
                    $auth = 'yes';
                }
            }

            if ($auth == 'yes') {

                $booking_game = Doctrine::getTable('Tripjacks_Model_BookingGame')->find($input->bookingid);
                $booking_game->paid = "Yes";
                $booking_game->save();
                $this->_redirect('/admin/bookings/booking/bookingindex/' . $input->bookingid . '/' . $input->venueid);
            } else {
                $this->_helper->getHelper('FlashMessenger')->addMessage('Why do this? This is not your venue.');
                $this->_redirect('/admin/bookings/booking/success');
            }
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }
    
    
    // action to modify an individual cms news
    public function buyleagueupdateAction() {

        // set filters and validators for GET input
        $filters = array(
            'subleagueid' => array('HtmlEntities', 'StripTags', 'StringTrim'),
            'playerid' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'subleagueid' => array('NotEmpty', 'Int'),
            'playerid' => array('NotEmpty', 'Int')
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());

        // test if input is valid
        // read array of record identifiers
        // delete records from database
        if ($input->isValid()) {


            $the_auth = Zend_Auth::getInstance()->getIdentity();
            $userid = $the_auth[0]["userid"];
            $access = $the_auth[0]["access"];


            if ($access == 'player') {


                $subleague = new Tripjacks_Model_BuyLeague;
                $subleague->date = date('Y-m-d');
                $subleague->playerid = $input->playerid;
                $subleague->paid = "Yes";
                $subleague->sub_leagueid = $input->subleagueid;
                $subleague->save();


                $this->_redirect('/leagues/league/specialindex/' . $input->subleagueid);
            } else {
                $this->_helper->getHelper('FlashMessenger')->addMessage('Why do this? You are not registered.');
                $this->_redirect('/admin/bookings/booking/success');
            }
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }

    // action to modify an individual cms news
  public function bookupdateAction()
  {
    // set filters and validators for GET input
        $filters = array(
            'bookingid' => array('HtmlEntities', 'StripTags', 'StringTrim'),
            'venueid' => array('HtmlEntities', 'StripTags', 'StringTrim')
      
        );
        $validators = array(
            'bookingid' => array('NotEmpty', 'Int'),
            'venueid' => array('NotEmpty', 'Int')
          
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());
        if ($input->isValid()) {

            $booking_players = new Tripjacks_Model_BookingPlayer;

            $count = $booking_players->countPlaying($input->bookingid);
            $names = $booking_players->getName($input->bookingid);
            $paid = $booking_players->getPaid($input->bookingid);
 
            if ($count == NULL) {
                $count = 0;
            }
            $slots = 16;
            $slots_avail = $slots - $count;
            $slot_place = $count + 1;
            
                $the_auth = Zend_Auth::getInstance()->getIdentity();
                $userid = $the_auth[0]["userid"];
                $access = $the_auth[0]["access"];

                $player_info = $booking_players->getPlayerid($userid);
                $playerid = $player_info['playerid'];
                $count_player = $booking_players->countPlayer($playerid,$input->bookingid);
                $player = $player_info['player'];
   
                
                if ($count_player == 0){

                $booking_player = new Tripjacks_Model_BookingPlayer;
                $booking_player->playerid = $playerid;
                $booking_player->name = $player;
                $booking_player->slot = $slot_place;
                $booking_player->booking_gameid = $input->bookingid;
                $booking_player->save();
            }
            $this->_redirect('/admin/bookings/booking/bookingindex/' . $input->bookingid . '/' . $input->venueid);
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }

   // action to display an individual ccms news feeds
    public function displayAction() {
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
                    ->from('Tripjacks_Model_News n')
                    ->where('n.newsid = ?', $input->id);
            $result = $q->fetchArray();
            if (count($result) == 1) {
                $this->view->news = $result[0];
            } else {
                throw new Zend_Controller_Action_Exception('Page not found', 404);
            }
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }

    // success action
    public function successAction() {
        if ($this->_helper->getHelper('FlashMessenger')->getMessages()) {
            $this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();
        } else {
            $this->_redirect('/home');
        }
    }

}

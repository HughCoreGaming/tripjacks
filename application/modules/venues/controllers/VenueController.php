<?php
class Venues_VenueController extends Zend_Controller_Action
{  
  public function init() 
  {
    $this->view->doctype('XHTML1_STRICT');
    $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/center.css');
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


            $game_dates = new Tripjacks_Model_BookingGame;
            $games = $game_dates->getGameDate($input->venueid);

            if (Zend_Auth::getInstance()->hasIdentity()) {
                $hasid = 1;
            } else {
                $hasid = 0;
            }           


            $this->view->hasid = $hasid;
            $this->view->records = $games;
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }
    
    
    
    
    
    
        // action to display list of venues venue
    public function bookingindexAction() {
        // set filters and validators for GET input
        $filters = array(
            'bookingid' => array('HtmlEntities', 'StripTags', 'StringTrim'),
            'trigger' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'bookingid' => array('NotEmpty', 'Int')
          
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
            $slots = 24;
            $slots_avail = $slots - $count;
            $slot_place = $count + 1;
            
                $the_auth = Zend_Auth::getInstance()->getIdentity();
                $userid = $the_auth[0]["userid"];
                $access = $the_auth[0]["access"];

            if ($input->trigger == "book") {



                $player_info = $booking_players->getPlayerid($userid);
                $playerid = $player_info['playerid'];
                $player = $player_info['player'];

                $booking_player = new Tripjacks_Model_BookingPlayer;
                $booking_player->playerid = $playerid;
                $booking_player->name = $player;
                $booking_player->slot = $slot_place;
                $booking_player->booking_gameid = $input->bookingid;
                $booking_playerid = $booking_player->save();
            }
            
            
            if ($input->trigger == "buy") {
                
                $booking_game = Doctrine::getTable('Tripjacks_Model_BookingGame')->find($input->id); 
                $booking_game->paid = "Yes";
                $booking_game->save();
                
            }

            $this->view->names = $names;
            $this->view->slot_place = $slot_place;
            $this->view->paid = $paid;
            $this->view->access = $access;

            
            
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }
    
  
  
  // action to display a venues venue
    public function displayAction()
  {
    $q = Doctrine_Query::create()
          ->from('Tripjacks_Model_Venue v')
            ->where('v.type = ?', 'normal');
    $result = $q->fetchArray();
    $this->view->venue = $result; 
  }
  
  public function createAction()
  {

     // generate input form
        $form = new Tripjacks_Form_VenueCreate;
        $this->view->form = $form;

        // test for valid input
        // if valid, populate model
        // assign default values for some fields
        // save to database
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $input = $this->getRequest()->getPost();
                
                // Get a salt using our function 
               $users_for_salt = new Tripjacks_Model_Users;
               $rand = $users_for_salt->generate_salt();


                // Now encrypt the password using that salt
   	       $encrypted = md5(md5($input["password"]).$rand);
                
               $users = new Tripjacks_Model_Users;
               $users->username = $input["username"];
               $users->password = $encrypted;
               $users->access = "venue";
               $users->rand = $rand;
               $users->save();
               $userid = $users->userid; 
              
                           
                $venue = new Tripjacks_Model_Venue;         
                $venue->name=$input["name"];
                $venue->address=$input["address"];
                $venue->town=$input["town"];
                $venue->county=$input["county"];
                $venue->postcode=$input["postcode"];
                $venue->day=$input["day"];
                $venue->start=$input["start"];
                $venue->email=$input["email"];
                $venue->propic="nopic.JPG";
                $venue->type="normal";
                $venue->regdate = date("Y-m-d");
                $venue->userid=$userid;
                $venue->save();
                $venueid = $venue->venueid;
                
                
                  //member number generator
                $gen_functions = new Tripjacks_Model_Users;
                $rand_chr = $gen_functions->randLetter();
                $rand_chr2 = $gen_functions->randLetter();
                $rand_int = $gen_functions->generate_salt();
                
               
                $arr = array($rand_chr, $rand_chr2, $rand_chr, $rand_int);
                $newmemberno = join("", $arr);
                
                $player_count=$gen_functions->player_memberno_exists($newmemberno);
                $venue_count=$gen_functions->venue_memberno_exists($newmemberno);
                
                while($player_count>=1&&$venue_count>=1){
                    
                $player_count=$gen_functions->player_memberno_exists($newmemberno);
                $venue_count=$gen_functions->venue_memberno_exists($newmemberno);
                    
                 
                //member number generator
                $gen_functions = new Tripjacks_Model_Users;
                $rand_chr = $gen_functions->randLetter();
                $rand_chr2 = $gen_functions->randLetter();
                $rand_int = $gen_functions->generate_salt();
                
               
                $arr = array($rand_chr, $rand_chr2, $rand_chr, $rand_int);
                $newmemberno = join("", $arr);
                     
                    
                }
  
                
                
                $the_venue_memberno = Doctrine::getTable('Tripjacks_Model_Venue')->find($venueid);   
                $the_venue_memberno->memberno = $newmemberno;
                $the_venue_memberno->save();
                $json_member_no = json_encode($newmemberno);
                
                $startdate = $venue->dispell_dates($input["day"]);
                $dates = $venue->add_week($startdate); 

                foreach ($dates as $value) {

                    $newDate = date("Y-m-d", strtotime($value));
                    $newDateUK = date("d/m/Y", strtotime($value));
                    $year = substr($newDateUK, 6, 10);
                    $month = substr($newDateUK, 3, 2);
                    $season = $venue->get_season($month, $year);
                    $played = "No";
                    $paid = "Yes";
                    $type = "venue";

                    $booking_game = new Tripjacks_Model_BookingGame;
                    $booking_game->date = $newDate;
                    $booking_game->season = $season;
                    $booking_game->played = $played;
                    $booking_game->venueid = $venueid;
                    $booking_game->paid = $paid;
                    $booking_game->type = $type;
                    $booking_gameid = $booking_game->save();
       
                }
                            
                    $this->_helper->getHelper('FlashMessenger')->addMessage('Thank You for registering! Here is your Member Number #' . $newmemberno .'. Please login to view your profile.');
                    $this->_helper->getHelper('FlashMessenger')->addMessage($json_member_no);
                    $this->_redirect('/venues/venue/success');
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
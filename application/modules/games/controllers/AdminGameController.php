<?php
class Games_AdminGameController extends Zend_Controller_Action
{    
    
    
    public function init() 
    {
    $this->view->doctype('XHTML1_STRICT');
    }


    // action to handle admin URLs
    public function preDispatch() 
    {
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


    // action to display list of cms news
    public function indexAction()
    {
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/center.css');

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

            $q = Doctrine_Query::create()
                    ->from('Tripjacks_Model_BookingPlayer bp')
                    ->where('bp.booking_gameid = ?', $input->bookingid);
            $result = $q->fetchArray(); 

            $this->view->venueid = $input->venueid; 
            $this->view->bookingid = $input->bookingid; 
            $this->view->records = $result; 

        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');                
        }              
    }

    
    public function getpicAction() 
    {
        $data = $this->_request->getPost();
        $player_info = new Tripjacks_Model_Player;
        $pic = Zend_Json::encode($player_info->getPics(10));
        echo $pic;
    }
      
        
    public function playerdetailsAction() 
    {
        $data = $this->_request->getPost();
        $booking_players = new Tripjacks_Model_BookingPlayer;
        $ids = $booking_players->playeridfromPlaying($data['bookingid']);
        $stack = array();

        foreach ($ids as $value) {
            $the_temp = $booking_players->playerinfo($value['pid']);
            array_push($stack, $the_temp);
        }

        $json_stack = Zend_Json::encode($stack);
        echo $json_stack;
        exit;
    }

    public function ajaxinsertresultsAction() 
    {

        $data = $this->_request->getPost();

        $booking_game_info = new Tripjacks_Model_BookingGame;
        $toll = 0;
        $ids = $data["ids"];
        $date = $booking_game_info->get_date($ids);
        $season = $booking_game_info->get_season_from_id($ids);
        $vid = $data["venueid"];

        $game = new Tripjacks_Model_Games;
        $game->season = $season;
        $game->date = $date;
        $game->venueid = $vid;
        $game->venuetotal = $toll;
        $game->save();
        $gameid = $game->gameid;
        

        $count = $data["count"];
        $counter = 1;
        $dec = 1;

        $stack = array();
        $stack = $data["playerid"];
        $many = $data["count"];

        $total = $many * 200;

        $percent[1] = "5";
        $percent[2] = "6";
        $percent[3] = "7";
        $percent[4] = "9";
        $percent[5] = "10";
        $percent[6] = "13";
        $percent[7] = "20";
        $percent[8] = "30";

        $counter = 1;

        while ($counter <= $many) {
            $sort = array_pop($percent);
            if ($counter >= 9) {
                $score = 50;
            } else {
                $score = $total * $sort / 100;
            }

            $playerid = array_pop($stack);
     
            $attendance_info = new Tripjacks_Model_Attendance;
            $attendanceid = $attendance_info->getAttendanceid($vid, $playerid);

            $results = new Tripjacks_Model_Results;
            $results->score = $score;
            $results->gameid = $gameid;
            $results->attendanceid = $attendanceid;
            $results->playerid = $playerid;
            $results->save();
            

            $counter++;
        }
        
            $booking_game_model = Doctrine::getTable('Tripjacks_Model_BookingGame')->find($ids);   
            $booking_game_model->played = "yes";
            $booking_game_model->save();
            exit;
    }

  // action to delete cms news
  public function presentupdateAction()
  {
    // set filters and validators for POST input
    $filters = array(
      'ids' => array('HtmlEntities', 'StripTags', 'StringTrim'),
        'bookingid'  => array('HtmlEntities', 'StripTags', 'StringTrim'),
        'venueid'  => array('HtmlEntities', 'StripTags', 'StringTrim'),
        'times'  => array('HtmlEntities', 'StripTags', 'StringTrim')
    );    
    $validators = array(
      'ids' => array('NotEmpty', 'Int'),
         'bookingid' => array('NotEmpty', 'Int'),
        'venueid' => array('NotEmpty', 'Int'),
        'times' => array('NotEmpty')
    );
    $input = new Zend_Filter_Input($filters, $validators);
    $input->setData($this->getRequest()->getParams());
    
    // test if input is valid
    // read array of record identifiers
    // delete records from database
    if ($input->isValid()) {
        
        foreach ($input->ids as $r) {

            $attendance_info = new Tripjacks_Model_Attendance;
            $count = $attendance_info->getcountAttendanceid($input->venueid, $r);

            if ($count ==0){
                $attendance = new Tripjacks_Model_Attendance;
                $attendance->venueid = $input->venueid;
                $attendance->playerid = $r;
                $attendance->save();           
            }     

        }
       
      $q = Doctrine_Query::create()
            ->update('Tripjacks_Model_BookingPlayer bp')
              ->set('bp.playing', '?', 'Yes')
            ->whereIn('bp.booking_playerid', $input->ids);
      
      $result = $q->execute();               
      $this->_redirect('/admin/games/game/display/'.$input->bookingid.'/'.$input->venueid.'/'.$input->times);
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }
  
  
  // action to modify an individual cms news
  public function updateAction()
  {
    // generate input form
    $form = new Tripjacks_Form_Setup;
    $this->view->form = $form;    
    
    if ($this->getRequest()->isPost()) {
      // if POST request
      // test if input is valid
      // retrieve current record
      // update values and replace in database
      $postData = $this->getRequest()->getPost();
 
      if ($form->isValid($postData)) {
        $input = $form->getValues();
        $news = Doctrine::getTable('Tripjacks_Model_BookingPlayer bp')->find($input['bookingid']);        
        $news->fromArray($input);
        $news->save();
        $this->_helper->getHelper('FlashMessenger')->addMessage('The record was successfully updated.');
        $this->_redirect('/admin/cms/news/success');        
      }      
    } else {    
      // if GET request
      // set filters and validators for GET input
      // test if input is valid
      // retrieve requested record
      // pre-populate form
      $filters = array(
        'bookingid' => array('HtmlEntities', 'StripTags', 'StringTrim')
      );          
      $validators = array(
        'bookingid' => array('NotEmpty', 'Int')
      );  
      $input = new Zend_Filter_Input($filters, $validators);
      $input->setData($this->getRequest()->getParams());      
      if ($input->isValid()) {
        $q = Doctrine_Query::create()
              ->from('Tripjacks_Model_BookingPlayer bp')
              ->where('bp.booking_gameid = ?', $input->bookingid);
        $result = $q->fetchArray();        
        if (count($result) > 1) {
          // perform adjustment for date selection lists

          $this->view->form->populate($result[0]);                
        } else {
          throw new Zend_Controller_Action_Exception('Page not found', 404);        
        }        
      } else {
        throw new Zend_Controller_Action_Exception('Invalid input');                
      }              
    }
  }  
  

  
  // action to display an individual ccms news feeds
    public function displayAction() 
    {

        $this->_helper->layout->setLayout('game');
        // set filters and validators for GET input
        $filters = array(
            'bookingid' => array('HtmlEntities', 'StripTags', 'StringTrim'),
            'venueid' => array('HtmlEntities', 'StripTags', 'StringTrim'),
            'times' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'bookingid' => array('NotEmpty', 'Int'),
            'venueid' => array('NotEmpty', 'Int'),
            'times' => array('NotEmpty')
        );
       
        
    $input = new Zend_Filter_Input($filters, $validators);
    $input->setData($this->getRequest()->getParams());
    
    // test if input is valid
    // read array of record identifiers
    // delete records from database
    if ($input->isValid()) {

            $booking_players = new Tripjacks_Model_BookingPlayer;
            $booking_game = new Tripjacks_Model_BookingGame;
            
            $ids = $booking_players->playeridfromPlaying($input->bookingid);

            $type = $booking_game->getType($input->bookingid);
            $stack = array();

            foreach ($ids as $value) {

                $the_temp = $booking_players->playerinfo($value['pid']);
                array_push($stack, $the_temp);
            }
            
            $json_stack = json_encode($stack);
 
            $this->view->type = json_encode($type);
            $this->view->venueid = json_encode($input->venueid);
            $this->view->times = json_encode($input->times);
            $this->view->bookingid = json_encode($input->bookingid);
            $this->view->players = $json_stack;
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }
    
    
    
  // success action
  public function successAction()
  {
    if ($this->_helper->getHelper('FlashMessenger')->getMessages()) {
      $this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();    
    } else {
      $this->_redirect('/admin/cms/news/index');    
    } 
  }
  
    
}

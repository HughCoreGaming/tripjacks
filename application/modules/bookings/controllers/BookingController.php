<?php
class Bookings_BookingController extends Zend_Controller_Action
{  
  public function init() 
  {
    $this->view->doctype('XHTML1_STRICT');
  }

  // action to display a cms news
  public function displayAction()
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
            ->from('Tripjacks_Model_News i')
            ->where('i.newsid = ?', $input->id);

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
  
  
  
  
    // action to display list of venues venue
    public function gamedisplayAction() {
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

              $this->view->venueid = $input->venueid;
            $this->view->records = $venuegames;
            $this->view->records2 = $skirmishgames;
            $this->view->game_players = $game_players;
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }
    
    
    
    
    
    
        // action to display list of venues venue
    public function bookingdisplayAction() {
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
            $booking_game = new Tripjacks_Model_BookingGame;

            $count = $booking_players->countPlaying($input->bookingid);
            $names = $booking_players->getName($input->bookingid);
            $get_date = $booking_game->get_date($input->bookingid);

 
            if ($count == NULL) {
                $count = 0;
            }
            $slots = 16;
            $slots_avail = $slots - $count;
            $slot_place = $count + 1;
            

            


            $this->view->names = $names;
            $this->view->slot_place = $slot_place;



            
            
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
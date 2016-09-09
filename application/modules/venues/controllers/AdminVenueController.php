<?php
class Venues_AdminVenueController extends Zend_Controller_Action
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
 
  
  // action to display list of venues
  public function indexAction()
  {
      $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/center.css');
      
    $q = Doctrine_Query::create()
          ->from('Tripjacks_Model_Venue v')
            ->where('v.type = ?', "normal");;
    $result = $q->fetchArray();
    
        $q2 = Doctrine_Query::create()
          ->from('Tripjacks_Model_Venue v')
            ->where('v.type = ?', "special");;
    $result2 = $q2->fetchArray();
    
    $this->view->records = $result; 
    $this->view->records2 = $result2; 
  }

  // action to delete venue
  public function deleteAction()
  {
  // set filters and validators for POST input
    $filters = array(
      'ids' => array('HtmlEntities', 'StripTags', 'StringTrim'),
        'userid' => array('HtmlEntities', 'StripTags', 'StringTrim')
    );    
    $validators = array(
      'ids' => array('NotEmpty', 'Int'),
        'userid' => array('NotEmpty', 'Int')
    );
    $input = new Zend_Filter_Input($filters, $validators);
    $input->setData($this->getRequest()->getParams());
    $userids=array();
    // test if input is valid
    // read array of record identifiers
    // delete records from database
    if ($input->isValid()) {
        
       foreach ($input->ids as $venueid){
          
              $q = Doctrine_Query::create()
                 ->select('userid')
                 ->from('Tripjacks_Model_Venue v')
                 ->where('v.venueid = ?', $input->ids);
              $userid = $q->fetchArray();
              
              array_push($userids, $userid[0]['userid']);
      }
      

      $q = Doctrine_Query::create()
            ->delete('Tripjacks_Model_Venue v')
            ->whereIn('v.venueid', $input->ids);
      $result = $q->execute();  
      


      $q = Doctrine_Query::create()
            ->delete('Tripjacks_Model_Users u')
            ->whereIn('u.userid', $userids);
      $result2 = $q->execute();     
      
      $this->_helper->getHelper('FlashMessenger')->addMessage('The venue successfully deleted.');
      $this->_redirect('/admin/venues/venue/success');
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }
  
  
  
  
   // action to display list of cms news
    public function profileAction() {
        
   
         $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/venueprofile.css');
         $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/lavalamp_test.css');
         $this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.easing.min.js');
         $this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.lavalamp.min.js');
        
       // set filters and validators for GET input
        $filters = array(
            'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'id' => array('NotEmpty', 'Int')
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());

        $the_auth = Zend_Auth::getInstance()->getIdentity();
        $userid = $the_auth[0]["userid"];
        $access = $the_auth[0]["access"];

        if ($input->isValid()) {

            $user_info = new Tripjacks_Model_Player;

            $result = $user_info->getVenueinfo($userid);

            if (count($result) == 1) {
                $this->view->user_info = $result[0];
                $this->view->venueid = json_encode($input->id);
            } else {
                throw new Zend_Controller_Action_Exception('Page not found', 404);
            }
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }
  
  
  
  public function uploadAction() {
      
      $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/venueprofile.css');

// generate input form
        $form = new Tripjacks_Form_VenueUpload;
        $this->view->form = $form;

        $the_auth = Zend_Auth::getInstance()->getIdentity();
        $userid = $the_auth[0]["userid"];
        $access = $the_auth[0]["access"];


        $user_info = new Tripjacks_Model_Player;

        $result = $user_info->getVenueinfo($userid);



        if ($this->getRequest()->isPost()) {
            // if POST request
            // test if input is valid
            // retrieve current record
            // update values and replace in database
            $postData = $this->getRequest()->getPost();

            if ($form->isValid($postData)) {
                $input = $form->getValues();
                
                
                $config_del = $this->getInvokeArg('bootstrap')->getOption('venuepro');
                $file = "{$config_del['uploadPath']}/{$result[0]['propic']}";
                unlink($file);
                
                $arr2 = array($result[0]['memberno'], $input['propic']);
                $newpropic = join("_", $arr2);


                $upload = Doctrine::getTable('Tripjacks_Model_Venue')->find($result[0]['venueid']);
                $upload->propic = $newpropic;
                $upload->save();

                $memberno = $result[0]['memberno'];
                $minus_ext = substr($input['propic'], 0, -4);
       
             
                $config_add = $this->getInvokeArg('bootstrap')->getOption('venuepro');
                $form->propic->setDestination($config_add['uploadPath']);
                $adapter = $form->propic->getTransferAdapter();
                $xt = @pathinfo($adapter->getFileName('propic'), PATHINFO_EXTENSION);
                $adapter->clearFilters();
                $adapter->addFilter('Rename', array(
                    'target' => sprintf('%d_%s.%s', $memberno, $minus_ext, $xt),
                    'overwrite' => true
                ));
                $adapter->receive('propic');

                $this->_redirect('/admin/venues/venue/profile');
          } 
    }
   }
    
    
  public function uploadspecialAction() {
      
        // generate input form
           $form = new Tripjacks_Form_VenueUploadspecial;
        $this->view->form = $form;


        if ($this->getRequest()->isPost()) {
            // if POST request
            // test if input is valid
            // retrieve current record
            // update values and replace in database
            $postData = $this->getRequest()->getPost();

            if ($form->isValid($postData)) {
                $input = $form->getValues();
                
                        $user_info = new Tripjacks_Model_Venue;

        $result = $user_info->getVenue($input['venueid']);
  
                
                $config_del = $this->getInvokeArg('bootstrap')->getOption('venuepro');
                $file = "{$config_del['uploadPath']}/{$result[0]['propic']}";
                unlink($file);
                
                $arr2 = array($result[0]['memberno'], $input['propic']);
                $newpropic = join("_", $arr2);


                $upload = Doctrine::getTable('Tripjacks_Model_Venue')->find($result[0]['venueid']);
                $upload->propic = $newpropic;
                $upload->save();

                $memberno = $result[0]['memberno'];
                $minus_ext = substr($input['propic'], 0, -4);
       
             
                $config_add = $this->getInvokeArg('bootstrap')->getOption('venuepro');
                $form->propic->setDestination($config_add['uploadPath']);
                $adapter = $form->propic->getTransferAdapter();
                $xt = @pathinfo($adapter->getFileName('propic'), PATHINFO_EXTENSION);
                $adapter->clearFilters();
                $adapter->addFilter('Rename', array(
                    'target' => sprintf('%d_%s.%s', $memberno, $minus_ext, $xt),
                    'overwrite' => true
                ));
                $adapter->receive('propic');

                $this->_redirect('/admin/venues/venue/display/'.$input["venueid"]);
      }      
    } else {    
      // if GET request
      // set filters and validators for GET input
      // test if input is valid
      // retrieve requested record
      // pre-populate form
      $filters = array(
        'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
      );          
      $validators = array(
        'id' => array('NotEmpty', 'Int')
      );  
      $input = new Zend_Filter_Input($filters, $validators);
      $input->setData($this->getRequest()->getParams());      
      if ($input->isValid()) {
        $q = Doctrine_Query::create()
              ->from('Tripjacks_Model_Venue v')
              ->where('v.venueid = ?', $input->id);
        $result = $q->fetchArray();        
        if (count($result) == 1) {
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
 
  
 
  // action to modify an individual venue
  public function updateAction()
  {
    // generate input form
    $form = new Tripjacks_Form_VenueUpdate;
    $this->view->form = $form;    
    
    if ($this->getRequest()->isPost()) {
      // if POST request
      // test if input is valid
      // retrieve current record
      // update values and replace in database
      $postData = $this->getRequest()->getPost();
 
      if ($form->isValid($postData)) {
        $input = $form->getValues();
        $venue = Doctrine::getTable('Tripjacks_Model_Venue')->find($input['venueid']); 
        $venue->fromArray($input);
        $venue->save();
     
  
                
        $this->_helper->getHelper('FlashMessenger')->addMessage('The record was successfully updated.');
        $this->_redirect('/admin/venues/venue/success');        
      }      
    } else {    
      // if GET request
      // set filters and validators for GET input
      // test if input is valid
      // retrieve requested record
      // pre-populate form
      $filters = array(
        'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
      );          
      $validators = array(
        'id' => array('NotEmpty', 'Int')
      );  
      $input = new Zend_Filter_Input($filters, $validators);
      $input->setData($this->getRequest()->getParams());      
      if ($input->isValid()) {
        $q = Doctrine_Query::create()
              ->from('Tripjacks_Model_Venue v')
              ->where('v.venueid = ?', $input->id);
        $result = $q->fetchArray();        
        if (count($result) == 1) {
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
  
  
  
 
  // action to modify an individual venue
  public function specialupdateAction()
  {
    // generate input form
    $form = new Tripjacks_Form_VenuespecialUpdate;
    $this->view->form = $form;    
    
    if ($this->getRequest()->isPost()) {
      // if POST request
      // test if input is valid
      // retrieve current record
      // update values and replace in database
      $postData = $this->getRequest()->getPost();
 
      if ($form->isValid($postData)) {
        $input = $form->getValues();
        $venue = Doctrine::getTable('Tripjacks_Model_Venue')->find($input['venueid']);    
        $venue->fromArray($input);
        $venue->save();
     
                
                
        $this->_helper->getHelper('FlashMessenger')->addMessage('The record was successfully updated.');
        $this->_redirect('/admin/venues/venue/success');        
      }      
    } else {    
      // if GET request
      // set filters and validators for GET input
      // test if input is valid
      // retrieve requested record
      // pre-populate form
      $filters = array(
        'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
      );          
      $validators = array(
        'id' => array('NotEmpty', 'Int')
      );  
      $input = new Zend_Filter_Input($filters, $validators);
      $input->setData($this->getRequest()->getParams());      
      if ($input->isValid()) {
        $q = Doctrine_Query::create()
              ->from('Tripjacks_Model_Venue v')
              ->where('v.venueid = ?', $input->id);
        $result = $q->fetchArray();        
        if (count($result) == 1) {
          // perform adjustment for date selection lists

          $this->view->form->populate($result[0]);                
        } else {
          throw new Zend_Controller_Action_Exception('Page not found', 404);        
        }        
      } else {
        throw new Zend_Controller_Action_Exception('Invalid input');                
      }              
    }}
  
   public function specialcreateAction() {
        // generate input form
        $form = new Tripjacks_Form_VenuespecialCreate;
        $this->view->form = $form;

        // test for valid input
        // if valid, populate model
        // assign default values for some fields
        // save to database
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $input = $this->getRequest()->getPost();

                $the_auth = Zend_Auth::getInstance()->getIdentity();
                $userid = $the_auth[0]["userid"];
                
                $na = "N/A";

                $venue = new Tripjacks_Model_Venue;
                $venue->name = $input["name"];
                $venue->address = $na;
                $venue->postcode = $na;
                $venue->day = $na;
                $venue->start = $na;
                $venue->propic = "nopic.JPG";
                $venue->type = "special";
                $venue->userid = $userid;
                $venue->save();
                $venueid = $venue->venueid;

                //member number generator
                $standard = "20";
                $arr = array($standard, $venueid);
                $newmemberno = join("", $arr);
                $the_venue_memberno = Doctrine::getTable('Tripjacks_Model_Venue')->find($venueid);
                $the_venue_memberno->memberno = $newmemberno;
                $the_venue_memberno->save();


                $startdate = $venue->dispell_dates($input["day"]);
                $dates = $venue->add_week($startdate);

                foreach ($dates as $value) {

                    $newDate = date("d/m/Y", strtotime($value));
                    $year = substr($newDate, 6, 10);
                    $month = substr($newDate, 3, 2);
                    $season = $venue->get_season($month, $year);
                    $playerid = 0;

                    $booking_game = new Tripjacks_Model_BookingGame;
                    $booking_game->playerid = $playerid;
                    $booking_game->date = $newDate;
                    $booking_game->season = $season;
                    $booking_game->playerid = $playerid;
                    $booking_game->venueid = $venueid;
                    $booking_gameid = $booking_game->save();
                }

                $this->_helper->getHelper('FlashMessenger')->addMessage('Your submission has been accepted as venue #' . $venueid . '.');
                $this->_redirect('admin/venues/venue/success');
            }
        }
    }
    
    
    
   public function createAction() {
        // generate input form
        $form = new Tripjacks_Form_VenuesCreate;
        $this->view->form = $form;

        // test for valid input
        // if valid, populate model
        // assign default values for some fields
        // save to database
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $input = $this->getRequest()->getPost();

                $the_auth = Zend_Auth::getInstance()->getIdentity();
                $userid = $the_auth[0]["userid"];

                $venue = new Tripjacks_Model_Venue;
                $venue->name = $input["name"];
                $venue->address = $input["address"];
                $venue->postcode = $input["postcode"];
                $venue->day = $input["day"];
                $venue->start = $input["start"];
                $venue->propic = "nopic.JPG";
                $venue->type = "normal";
                $venue->userid = $userid;
                $venue->save();
                $venueid = $venue->venueid;

                //member number generator
                $standard = "20";
                $arr = array($standard, $venueid);
                $newmemberno = join("", $arr);
                $the_venue_memberno = Doctrine::getTable('Tripjacks_Model_Venue')->find($venueid);
                $the_venue_memberno->memberno = $newmemberno;
                $the_venue_memberno->save();


                $startdate = $venue->dispell_dates($input["day"]);
                $dates = $venue->add_week($startdate);

                foreach ($dates as $value) {

                    $newDate = date("d/m/Y", strtotime($value));
                    $year = substr($newDate, 6, 10);
                    $month = substr($newDate, 3, 2);
                    $season = $venue->get_season($month, $year);
                    $playerid = 0;

                    $booking_game = new Tripjacks_Model_BookingGame;
                    $booking_game->playerid = $playerid;
                    $booking_game->date = $newDate;
                    $booking_game->season = $season;
                    $booking_game->playerid = $playerid;
                    $booking_game->venueid = $venueid;
                    $booking_gameid = $booking_game->save();
                }

                $this->_helper->getHelper('FlashMessenger')->addMessage('Your submission has been accepted as venue #' . $venueid . '.');
                $this->_redirect('admin/venues/venue/success');
            }
        }
    }

    // action to display an individual ccms venue feeds
  public function displayAction()
  {
  
         $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/profile.css');
        
       // set filters and validators for GET input
        $filters = array(
            'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'id' => array('NotEmpty', 'Int')
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());


        if ($input->isValid()) {

            $user_info = new Tripjacks_Model_Venue;

            $result = $user_info->getVenue($input->id);

            if (count($result) == 1) {
                $this->view->user_info = $result[0];
            } else {
                throw new Zend_Controller_Action_Exception('Page not found', 404);
            }
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
  }      
  
  
   // action to display an individual ccms venue feeds
  public function specialdisplayAction()
  {
  
         $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/profile.css');
        
       // set filters and validators for GET input
        $filters = array(
            'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'id' => array('NotEmpty', 'Int')
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());


        if ($input->isValid()) {

            $user_info = new Tripjacks_Model_Venue;

            $result = $user_info->getVenue($input->id);

            if (count($result) == 1) {
                $this->view->user_info = $result[0];
            } else {
                throw new Zend_Controller_Action_Exception('Page not found', 404);
            }
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
      $this->_redirect('/admin/venues/venue/index');    
    } 
  }
  
    
}

<?php

class Players_AdminPlayerController extends Zend_Controller_Action {

    public function init() {
        $this->view->doctype('XHTML1_STRICT');
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/center.css');
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
    
    
  // action to display list of players
  public function indexAction()
  {
    $q = Doctrine_Query::create()
          ->from('Tripjacks_Model_Player p')
          ->orderBy('p.lastname', 'ASC');
    $result = $q->fetchArray();
    $this->view->records = $result; 
  }

    // action to display list of cms news
    public function profileAction() {
        
   
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

        $the_auth = Zend_Auth::getInstance()->getIdentity();
        $userid = $the_auth[0]["userid"];
        $access = $the_auth[0]["access"];

        if ($input->isValid()) {

            $user_info = new Tripjacks_Model_Player;

            $result = $user_info->getPlayerinfo($userid);

            if (count($result) == 1) {
                $this->view->user_info = $result[0];
            } else {
                throw new Zend_Controller_Action_Exception('Page not found', 404);
            }
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }

// action to delete player
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
        
               foreach ($input->ids as $playerid){
          
              $q = Doctrine_Query::create()
                 ->select('userid')
                 ->from('Tripjacks_Model_Player p')
                 ->where('p.playerid = ?', $input->ids);
              $userid = $q->fetchArray();
              
              array_push($userids, $userid[0]['userid']);
      }
      
      
      $q = Doctrine_Query::create()
            ->delete('Tripjacks_Model_Player p')
            ->whereIn('p.playerid', $input->ids);
      $result = $q->execute();  
      
      $q = Doctrine_Query::create()
            ->delete('Tripjacks_Model_Attendance a')
            ->whereIn('a.playerid', $input->ids);
      $result = $q->execute();  
      
            $q = Doctrine_Query::create()
            ->delete('Tripjacks_Model_Results r')
            ->whereIn('r.playerid', $input->ids);
      $result = $q->execute();  

      $q = Doctrine_Query::create()
            ->delete('Tripjacks_Model_Users u')
            ->whereIn('u.userid', $userids);
      $result2 = $q->execute();     
      
      $this->_helper->getHelper('FlashMessenger')->addMessage('The records were successfully deleted.');
      $this->_redirect('/admin/cms/news/success');
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }
  

  
  // action to modify an individual players
    public function updateAction() {
        // generate input form
        $form = new Tripjacks_Form_PlayerUpdate;
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            // if POST request
            // test if input is valid
            // retrieve current record
            // update values and replace in database
            $postData = $this->getRequest()->getPost();

            if ($form->isValid($postData)) {
                $input = $form->getValues();


                //get userid 
                $q = Doctrine_Query::create()
                        ->from('Tripjacks_Model_Player p')
                        ->where('p.playerid = ?', $input['playerid']);
                $result = $q->fetchArray();
                

                $userid = $result[0]['userid'];

                if ($input['password'] != "") {

                    // Get a salt using our function 
                    $users_for_salt = new Tripjacks_Model_Users;
                    $rand = $users_for_salt->generate_salt();

                    // Now encrypt the password using that salt
                    $encrypted = md5(md5($input['password']) . $rand);
                }

                $users = Doctrine::getTable('Tripjacks_Model_Users')->find($userid);
                $users->username = $input['username'];

                if ($input['password'] != "") {
                    $users->password = $encrypted;
                }

                $users->access = "player";
                $users->rand = $rand;
                $users->save();
                $userid = $users->userid;

                $the_player = Doctrine::getTable('Tripjacks_Model_Player')->find($input['playerid']);
                $join_firstlast = array($input['firstname'], $input['lastname']);
                $player = join(" ", $join_firstlast);
                $the_player->firstname = $input['firstname'];
                $the_player->lastname = $input['lastname'];
                $the_player->address = $input["address"];
                $the_player->town = $input["town"];
                $the_player->county = $input["county"];
                $the_player->postcode = $input["postcode"];
                $the_player->player = $player;
                $the_player->email = $input['email'];
                $the_player->dob = $input['dob'];
                $the_player->sex = $input['sex'];
                $the_player->userid = $userid;
                $the_player->save();
                $playerid = $the_player->playerid;
                ;
                $this->_helper->getHelper('FlashMessenger')->addMessage('The record was successfully updated.');
                $this->_redirect('/admin/players/player/success');
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
                        ->from('Tripjacks_Model_Player p')
                        ->innerJoin('p.Tripjacks_Model_Users u')
                        ->where('p.playerid = ?', $input->id);
                $result = $q->fetchArray();


                if (count($result) == 1) {
                    // perform adjustment for date selection lists

                    $this->view->form->setDefault('username', $result[0]["Tripjacks_Model_Users"]["username"]);
                    $this->view->form->populate($result[0]);
                } else {
                    throw new Zend_Controller_Action_Exception('Page not found', 404);
                }
            } else {
                throw new Zend_Controller_Action_Exception('Invalid input');
            }
        }
    }
  
   public function createAction() {
        $form = new Tripjacks_Form_PlayerCreatetemp;
        $this->view->form = $form;

        // test for valid input
        // if valid, populate model
        // assign default values for some fields
        // save to database
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $values = $form->getValues();
                
               // Get a salt using our function 
               $users_for_salt = new Tripjacks_Model_Users;
               $rand = $users_for_salt->generate_salt();

                // Now encrypt the password using that salt
   	        $encrypted = md5(md5($values['password']).$rand);

                $users = new Tripjacks_Model_Users;
                $users->username = $values['username'];
                $users->password = $encrypted;
                $users->access = "player";
                $users->rand = $rand;
                $users->save();
                $userid = $users->userid;
                             
                $the_player = new Tripjacks_Model_Player;
                $the_player->firstname = $values['username'];
                $the_player->player = $values['username'];
                $the_player->propic = 'nopic.JPG';
                $the_player->regdate = date("Y-m-d");
                $the_player->userid = $userid;
                $the_player->save();
                $playerid = $the_player->playerid;
                                
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
  
                $the_player_memberno = Doctrine::getTable('Tripjacks_Model_Player')->find($playerid);   
                $the_player_memberno->memberno = $newmemberno;
                $the_player_memberno->save();
                
                $json_member_no = json_encode($newmemberno);
                
 

                $this->_helper->getHelper('FlashMessenger')->addMessage('Thanks you for Registering! Here is your Member Number #' . $newmemberno .'. Please login to view your profile.');
                $this->_helper->getHelper('FlashMessenger')->addMessage($json_member_no);
              
                $this->_redirect('admin/players/player/success');
            }
        }
    }
  
  

  
  public function uploadAction() {

// generate input form
        $form = new Tripjacks_Form_PlayerUpload;
        $this->view->form = $form;

        $the_auth = Zend_Auth::getInstance()->getIdentity();
        $userid = $the_auth[0]["userid"];
        $access = $the_auth[0]["access"];


        $user_info = new Tripjacks_Model_Player;

        $result = $user_info->getPlayerinfo($userid);



        if ($this->getRequest()->isPost()) {
            // if POST request
            // test if input is valid
            // retrieve current record
            // update values and replace in database
            $postData = $this->getRequest()->getPost();

            if ($form->isValid($postData)) {
                $input = $form->getValues();
                
                
                $config_del = $this->getInvokeArg('bootstrap')->getOption('playerpro');
                $file = "{$config_del['uploadPath']}/{$result[0]['propic']}";
                unlink($file);
                
                $arr2 = array($result[0]['memberno'], $input['propic']);
                $newpropic = join("_", $arr2);


                $upload = Doctrine::getTable('Tripjacks_Model_Player')->find($result[0]['playerid']);
                $upload->propic = $newpropic;
                $upload->save();

                $memberno = $result[0]['memberno'];
                $minus_ext = substr($input['propic'], 0, -4);
       
             
                $config_add = $this->getInvokeArg('bootstrap')->getOption('playerpro');
                $form->propic->setDestination($config_add['uploadPath']);
                $adapter = $form->propic->getTransferAdapter();
                $xt = @pathinfo($adapter->getFileName('propic'), PATHINFO_EXTENSION);
                $adapter->clearFilters();
                $adapter->addFilter('Rename', array(
                    'target' => sprintf('%d_%s.%s', $memberno, $minus_ext, $xt),
                    'overwrite' => true
                ));
                $adapter->receive('propic');

                $this->_redirect('/admin/players/player/profile');
          }  
    }
   }

// action to display an individual ccms news feeds
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
            ->from('Tripjacks_Model_Player p')
            ->where('p.playerid = ?', $input->id);
      $result = $q->fetchArray();
      if (count($result) == 1) {
        $this->view->user_info= $result[0];               
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
      $this->_redirect('/admin/cms/news/index');    
    } 
  }
  
    
}

<?php
class Leagues_AdminLeagueController extends Zend_Controller_Action
{    
  public function init() 
  {
    $this->view->doctype('XHTML1_STRICT');
    $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/center.css');
  }

  // action to handle admin URLs
  public function preDispatch() 
  {
    // set admin layout
    // check if user is authenticated
    // if not, redirect to login page
    $url = $this->getRequest()->getRequestUri();
    $this->_helper->layout->setLayout('admin');     
    $the_auth = Zend_Auth::getInstance()->getIdentity();
    $access = $the_auth[0]["access"];
    if (!Zend_Auth::getInstance()->hasIdentity()||$access!="admin") {
      $session = new Zend_Session_Namespace('tripjacks.auth');
      $session->requestURL = $url;
      $this->_redirect('/admin/login');
    }
  }
  
  // action to display list of leagues
    public function indexAction() {

        // pre-populate form
        $filters = array(
            'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'id' => array('NotEmpty', 'Int')
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());

        if (isset($input->id)) {
            
            $this->view->is = "yes";
            $q = Doctrine_Query::create()
                    ->from('Tripjacks_Model_SubLeague sl')
                    ->where('sl.leagueid = ?', $input->id);
            $result = $q->fetchArray();
  
                $this->view->leagueid = $input->id;
                $this->view->subleagues = $result;
                $this->view->count2 = count($result);

           
        }else{
              $this->view->is = "no";
        }


        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_SubLeague sl');
        $result = $q->fetchArray();
        $this->view->count = count($result);

        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_League l');
        $result = $q->fetchArray();
        $this->view->records = $result;
    }
    

    // action to create league
      public function createAction()
  {
    // generate input form
    $form = new Tripjacks_Form_LeagueCreate;
    $this->view->form = $form;
    
    // test for valid input
    // if valid, populate model
    // assign default values for some fields
    // save to database
    if ($this->getRequest()->isPost()) {
      if ($form->isValid($this->getRequest()->getPost())) {
        $league = new Tripjacks_Model_League;
        $league->fromArray($form->getValues());      
        $league->save();
        $id = $league->leagueid;  
        $this->_helper->getHelper('FlashMessenger')->addMessage('New league created!');
        $this->_redirect('/admin/leagues/league/success');
      }   
    } 
  }
  
      // action to create sub league
    public function createsubAction() {
    

        // set filters and validators for GET input
        $filters = array(
            'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'id' => array('NotEmpty', 'Int')
        );
        $the_get = new Zend_Filter_Input($filters, $validators);
        $the_get->setData($this->getRequest()->getParams());


        if ($the_get->isValid()) {
            
         // generate input form
        $form = new Tripjacks_Form_LeaguesubCreate;
 
        $form->leagueid->setValue($the_get->id);
        $this->view->form = $form;
     

            // test for valid input
            // if valid, populate model
            // assign default values for some fields
            // save to database
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->getRequest()->getPost())) {
                    $subleague = new Tripjacks_Model_SubLeague;
                    $subleague->fromArray($form->getValues());
                    $subleague->save();
                    $id = $subleague->subleagueid;
                    $this->_helper->getHelper('FlashMessenger')->addMessage('New sub league created!');
                    $this->_redirect('/admin/leagues/league/success');
                }
            }
        }
    }

    // action to delete league
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
            ->delete('Tripjacks_Model_League l')
            ->whereIn('l.leagueid', $input->ids);
      $result = $q->execute();               
      $this->_helper->getHelper('FlashMessenger')->addMessage('The records were successfully deleted.');
      $this->_redirect('/admin/leagues/league/success');
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }
  
      // action to delete sub league
  public function deletesubAction()
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
            ->delete('Tripjacks_Model_SubLeague sl')
            ->whereIn('sl.subleagueid', $input->ids);
      $result = $q->execute();               
      $this->_helper->getHelper('FlashMessenger')->addMessage('The records were successfully deleted.');
      $this->_redirect('/admin/leagues/league/success');
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }
  
  // action to modify an individual league
  public function updateAction()
  {
    // generate input form
    $form = new Tripjacks_Form_LeagueUpdate;
    $this->view->form = $form;    
    
    if ($this->getRequest()->isPost()) {
      // if POST request
      // test if input is valid
      // retrieve current record
      // update values and replace in database
      $postData = $this->getRequest()->getPost();
 
      if ($form->isValid($postData)) {
        $input = $form->getValues();
        $league = Doctrine::getTable('Tripjacks_Model_League')->find($input['leagueid']);        
        $league->fromArray($input);
        $league->save();
        $this->_helper->getHelper('FlashMessenger')->addMessage('The record was successfully updated.');
        $this->_redirect('/admin/leagues/league/success');     
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
              ->from('Tripjacks_Model_League l')
              ->where('l.leagueid = ?', $input->id);
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
  
  
  // action to modify an individual subleague
  public function updatesubAction()
  {
    // generate input form
    $form = new Tripjacks_Form_LeaguesubUpdate;
    $this->view->form = $form;    
    
    if ($this->getRequest()->isPost()) {
      // if POST request
      // test if input is valid
      // retrieve current record
      // update values and replace in database
      $postData = $this->getRequest()->getPost();
 
      if ($form->isValid($postData)) {
        $input = $form->getValues();
        $league = Doctrine::getTable('Tripjacks_Model_SubLeague')->find($input['subleagueid']);        
        $league->fromArray($input);
        $league->save();
        $this->_helper->getHelper('FlashMessenger')->addMessage('The record was successfully updated.');
        $this->_redirect('/admin/leagues/league/success');     
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
              ->from('Tripjacks_Model_SubLeague sl')
              ->where('sl.subleagueid = ?', $input->id);
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
  
  // action to display an individual leagues
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
            ->from('Tripjacks_Model_League l')
            ->where('l.leagueid = ?', $input->id);
      $result = $q->fetchArray();
      if (count($result) == 1) {
        $this->view->league = $result[0];               
      } else {
        throw new Zend_Controller_Action_Exception('Page not found', 404);        
      }
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }   
  
    public function uploadAction() {

// generate input form
        $form = new Tripjacks_Form_LeagueUpload;
        $this->view->form = $form;



        if ($this->getRequest()->isPost()) {
            // if POST request
            // test if input is valid
            // retrieve current record
            // update values and replace in database
            $postData = $this->getRequest()->getPost();

            if ($form->isValid($postData)) {
                $input = $form->getValues();


                $q = Doctrine_Query::create()
                        ->from('Tripjacks_Model_League l')
                        ->where('l.propic = ?', $input['pic']);
                $result = $q->fetchArray();
                if (count($result) > 0) {

                    $this->view->message = 'Please re-name!';
                } else {

                    $upload = new Tripjacks_Model_Leagues();
                    $upload->propic = $input['pic'];
                    $upload->save();


                    $config_add = $this->getInvokeArg('bootstrap')->getOption('leagueuploads');
                    $form->pic->setDestination($config_add['uploadPath']);
                    $adapter = $form->pic->getTransferAdapter();

                    $adapter->receive('pic');

                    $this->_redirect('/home');
                }
                
            }
        }
    }
       public function uploadsubAction() {

// generate input form
        $form = new Tripjacks_Form_LeaguesubUpload;
        $this->view->form = $form;



        if ($this->getRequest()->isPost()) {
            // if POST request
            // test if input is valid
            // retrieve current record
            // update values and replace in database
            $postData = $this->getRequest()->getPost();

            if ($form->isValid($postData)) {
                $input = $form->getValues();


                $q = Doctrine_Query::create()
                        ->from('Tripjacks_Model_SubLeague sl')
                        ->where('sl.propic = ?', $input['pic']);
                $result = $q->fetchArray();
                if (count($result) > 0) {

                    $this->view->message = 'Please re-name!';
                } else {

                    $upload = new Tripjacks_Model_Leagues();
                    $upload->propic = $input['pic'];
                    $upload->save();


                    $config_add = $this->getInvokeArg('bootstrap')->getOption('subleagueuploads');
                    $form->pic->setDestination($config_add['uploadPath']);
                    $adapter = $form->pic->getTransferAdapter();

                    $adapter->receive('pic');

                    $this->_redirect('/home');
                }
            }
        }
    }
   
  
  
    // action to display an individual sub leagues
  public function displaysubAction()
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
            ->where('sl.subleagueid = ?', $input->id);
      $result = $q->fetchArray();
      if (count($result) == 1) {
        $this->view->league = $result[0];               
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

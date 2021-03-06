<?php
class Mains_AdminMainController extends Zend_Controller_Action
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
  
  
  //create new main page
  public function createAction()
  {
       $this->view->headScript()->appendFile($this->view->baseUrl().'/includes/ckeditor/ckeditor.js');

    // generate input form
    $form = new Tripjacks_Form_MainCreate;
    $lastid = new Tripjacks_Model_Main;
    $lastidinserted = $lastid->getMain();
    
    $q = Doctrine_Query::create()
          ->from('Tripjacks_Model_Pics p');
    $result = $q->fetchArray();
    $this->view->records = $result;

    $this->view->form = $form;
    $this->view->lastid = $lastidinserted['mainid']; 
    
    // test for valid input
    // if valid, populate model
    // assign default values for some fields
    // save to database
    if ($this->getRequest()->isPost()) {
      if ($form->isValid($this->getRequest()->getPost())) {
        $news = new Tripjacks_Model_Main;
        $news->fromArray($form->getValues());      
        $news->save();
        $mainid = $news->mainid;  
        
     
         $config = $this->getInvokeArg('bootstrap')->getOption('mainuploads');        
        $form->images->setDestination($config['uploadPath']);
        $adapter = $form->images->getTransferAdapter();
        for($x=0; $x<$form->images->getMultiFile(); $x++) {
          $xt = @pathinfo($adapter->getFileName('images_'.$x.'_'), PATHINFO_EXTENSION);
          $adapter->clearFilters();
          $adapter->addFilter('Rename', array(
            'target' => sprintf('%d_%d.%s', $mainid, ($x), $xt), 
            'overwrite' => true
          ));
          $adapter->receive('images_'.$x.'_');
        }         
        $this->_helper->getHelper('FlashMessenger')->addMessage('Your submission has been accepted.');
        $this->_redirect('/home');
      }   
    } 
  }
  


  
  // action to display list of main pages
  public function indexAction()
  {
    $q = Doctrine_Query::create()
          ->from('Tripjacks_Model_Main m');
    $result = $q->fetchArray();
    $this->view->records = $result; 
  }

  
  
    // action to delete main page
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
            ->delete('Tripjacks_Model_Main m')
            ->whereIn('m.mainid', $input->ids);
      $result = $q->execute();      
       $config = $this->getInvokeArg('bootstrap')->getOption('mainuploads');                  
      foreach ($input->ids as $id) {
        foreach (glob("{$config['uploadPath']}/{$id}_*") as $file) {
          unlink($file);
        }     
      }
      $this->_helper->getHelper('FlashMessenger')->addMessage('The records were successfully deleted.');
      $this->_redirect('/admin/mains/main/success');
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }
  
  
    // action to delete pics
  public function deletepicAction()
  {
    // set filters and validators for POST input
    $filters = array(
      'ids' => array('HtmlEntities', 'StripTags', 'StringTrim'),
        'names' => array('HtmlEntities', 'StripTags', 'StringTrim')
    );    
    $validators = array(
      'ids' => array('NotEmpty', 'Int'),
        'names' => array('NotEmpty')
    );
    $input = new Zend_Filter_Input($filters, $validators);
    $input->setData($this->getRequest()->getParams());
    
    

    
    // test if input is valid
    // read array of record identifiers
    // delete records from database
    if ($input->isValid()) {
      $q = Doctrine_Query::create()
            ->delete('Tripjacks_Model_Pics p')
            ->whereIn('p.picsid', $input->ids);
      $result = $q->execute();      
      
$config_del = $this->getInvokeArg('bootstrap')->getOption('uploads');
       
      foreach ($input->names as $value) {    
          
                
                $file = "{$config_del['uploadPath']}/{$value}";
                unlink($file);
          
      }
      $this->_helper->getHelper('FlashMessenger')->addMessage('The records were successfully deleted.');
      $this->_redirect('/admin/mains/main/success');
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }
  
  
  
  // action to modify an individual main page
  public function updateAction()
  {
    // generate input form
    $form = new Tripjacks_Form_MainUpdate;
    $this->view->form = $form;    
    
    if ($this->getRequest()->isPost()) {
      // if POST request
      // test if input is valid
      // retrieve current record
      // update values and replace in database
      $postData = $this->getRequest()->getPost();
 
      if ($form->isValid($postData)) {
        $input = $form->getValues();
        $news = Doctrine::getTable('Tripjacks_Model_Main')->find($input['mainid']);        
        $news->fromArray($input);
        $news->save();
        $this->_helper->getHelper('FlashMessenger')->addMessage('The record was successfully updated.');
        $this->_redirect('/admin/mains/main/success');       
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
              ->from('Tripjacks_Model_Main m')
              ->where('m.mainid = ?', $input->id);
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
  
  // action to display an individual main page
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
            ->from('Tripjacks_Model_Main m')
            ->where('m.mainid = ?', $input->id);
      $result = $q->fetchArray();
      if (count($result) == 1) {
        $this->view->main = $result[0];  
      $config = Array();
        
          $this->view->images = array(); 
        $config = $this->getInvokeArg('bootstrap')->getOption('mainuploads');
                  

        foreach (glob("{$config['uploadPath']}/{$this->view->main['mainid']}_*") as $file) {

          $this->view->images[] = basename($file);
        }          
      } else {
        throw new Zend_Controller_Action_Exception('Page not found', 404);        
      }
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }      
  
  
  
  //upload pic
  public function uploadAction() {

// generate input form
        $form = new Tripjacks_Form_MainUpload;
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
                        ->from('Tripjacks_Model_Pics p')
                        ->where('p.name = ?', $input['pic']);
                $result = $q->fetchArray();
                if (count($result) > 0) {

                    $this->view->message = 'You could not be logged in. Please try again.';
                } else {

                    $upload = new Tripjacks_Model_Pics();
                    $upload->name = $input['pic'];
                    $upload->save();


                    $config_add = $this->getInvokeArg('bootstrap')->getOption('uploads');
                    $form->pic->setDestination($config_add['uploadPath']);
                    $adapter = $form->pic->getTransferAdapter();

                    $adapter->receive('pic');

                    $this->_redirect('/admin/mains/main/index');
                }
            }
        }
    }
   
   

  // success action
  public function successAction()
  {
    if ($this->_helper->getHelper('FlashMessenger')->getMessages()) {
      $this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();    
    } else {
      $this->_redirect('/admin/mains/main/index');    
    } 
  }
  
    
}

<?php
class Cms_AdminNewsController extends Zend_Controller_Action
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
  
  // action to display list of cms news
  public function indexAction()
  {
    $q = Doctrine_Query::create()
          ->from('Tripjacks_Model_News n');
    $result = $q->fetchArray();
    $this->view->records = $result; 
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
  public function updateAction()
  {
    // generate input form
    $form = new Tripjacks_Form_NewsUpdate;
    $this->view->form = $form;    
    
    if ($this->getRequest()->isPost()) {
      // if POST request
      // test if input is valid
      // retrieve current record
      // update values and replace in database
      $postData = $this->getRequest()->getPost();
 
      if ($form->isValid($postData)) {
        $input = $form->getValues();
        $news = Doctrine::getTable('Tripjacks_Model_News')->find($input['newsid']);        
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
        'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
      );          
      $validators = array(
        'id' => array('NotEmpty', 'Int')
      );  
      $input = new Zend_Filter_Input($filters, $validators);
      $input->setData($this->getRequest()->getParams());      
      if ($input->isValid()) {
        $q = Doctrine_Query::create()
              ->from('Tripjacks_Model_News n')
              ->where('n.newsid = ?', $input->id);
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
        $this->_helper->getHelper('FlashMessenger')->addMessage('Your submission has been accepted.');
        $this->_redirect('admin/cms/news/success');
      }   
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

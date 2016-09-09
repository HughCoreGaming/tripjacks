<?php
class Cms_NewsController extends Zend_Controller_Action
{  
  public function init() 
  {
    $this->view->doctype('XHTML1_STRICT');
  }

  // action to display a cms news
  public function displayAction()
  {
  
    $q = Doctrine_Query::create()
          ->from('Tripjacks_Model_News n');
    $result = $q->fetchArray();
    $this->view->news = $result; 
    
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
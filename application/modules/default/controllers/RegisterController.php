<?php
class RegisterController extends Zend_Controller_Action
{

  public function init()
  {
    $this->view->doctype('XHTML1_STRICT');
  }
  
  public function indexAction()
  {
  
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

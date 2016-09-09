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
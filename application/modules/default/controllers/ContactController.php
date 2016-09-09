<?php
class ContactController extends Zend_Controller_Action
{

  public function init()
  {
    $this->view->doctype('XHTML1_STRICT');

  }
  
  public function indexAction()
  {
    $form = new Tripjacks_Form_Contact;
    $this->view->form = $form;             
    if ($this->getRequest()->isPost()) {
      if ($form->isValid($this->getRequest()->getPost())) {
        $values = $form->getValues();
        
        $ip = $_SERVER['REMOTE_ADDR'];
                $content = "You have a customer enquiry:

            '" . $values['message'] . "'

            His name is: '" . $values['name'] . "'  and  IP address is:  .$ip.

            ";

        $mail = new Zend_Mail();
        $mail->setBodyText($content);
        $mail->setReplyTo('dippydoon100@yahoo.com', 'TripjacksPACO');
        $mail->setFrom($values['email'], $values['name']);
        $mail->addTo('dippydoon100@yahoo.com'); 
        $mail->setSubject('TripjacksPACO Enquiry');
        $mail->send();
        $this->_helper->getHelper('FlashMessenger')->addMessage('Thank you. Your message was successfully sent.');
        $this->_redirect('/contact/success');
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

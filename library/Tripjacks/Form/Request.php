<?php
class Tripjacks_Form_Request extends Zend_Form
{
  public function init()
  {
    // initialize form
    $this->setAction('/request')
         ->setMethod('post');
         
    // create text input for name 
    $username = new Zend_Form_Element_Text('username');
    $username->setLabel('Username:')
             ->setOptions(array('size' => '30'))
             ->setRequired(true)
             ->addValidator('Alnum')            
             ->addFilter('HtmlEntities')            
             ->addFilter('StringTrim');            
         
    // create text input for email address
    $email = new Zend_Form_Element_Text('email');
    $email->setLabel('Email address: ');
    $email->setOptions(array(
        'size' => '50',
        'id'=>'regemail'
        ))
          ->setRequired(true)
          ->addValidator('NotEmpty', true)
          ->addValidator('EmailAddress', true)            
          ->addFilter('HTMLEntities')            
          ->addFilter('StringToLower')        
          ->addFilter('StringTrim');   
    
         
    // create submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Request')
           ->setOptions(array('class' => 'submit'));
         
    // attach elements to form    
    $this->addElement($username)
         ->addElement($email)
         ->addElement($submit);     
    
  }
}

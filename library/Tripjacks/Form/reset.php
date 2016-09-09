<?php
class Tripjacks_Form_Reset extends Zend_Form
{
  public function init()
  {
    // initialize form
    $this->setAction('/reset')
         ->setMethod('post');
    
           // create hidden input for item ID
    $userid = new Zend_Form_Element_Hidden('userid');
    $userid->addValidator('Int')            
       ->addFilter('HtmlEntities')            
       ->addFilter('StringTrim');   
         
        // create text input for password
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password:')
                ->setOptions(array('size' => '30'))
                ->setRequired(true)
                ->addFilter('HtmlEntities')
                ->addFilter('StringTrim');
        
                // create text input for password
        $redopassword = new Zend_Form_Element_Password('redopassword');
        $redopassword->setLabel('Re-Type Password:')
                ->setOptions(array('size' => '30'))
                ->setRequired(true)
                ->addFilter('HtmlEntities')
                ->addFilter('StringTrim');
         
    // create submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Reset')
           ->setOptions(array('class' => 'submit'));
         
    // attach elements to form    
    $this->addElement($userid)
        ->addElement($password)
         ->addElement($redopassword)
         ->addElement($submit);     
    
  }
}

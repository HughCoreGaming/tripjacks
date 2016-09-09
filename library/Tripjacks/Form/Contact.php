<?php
class Tripjacks_Form_Contact extends Zend_Form
{
  public function init()
  {
    // initialize form
    $this->setAction('/contact/index')
         ->setMethod('post');
              

    // create text input for name 
    $name = new Zend_Form_Element_Text('name');
    $name->setLabel('contact-name:')
             ->setOptions(array('size' => '50'))
             ->setRequired(true)            
             ->addFilter('HtmlEntities')            
             ->addFilter('StringTrim');  



    // create text input for email address
    $email = new Zend_Form_Element_Text('email');
    $email->setLabel('contact-email-address');
    $email->setOptions(array('size' => '50'))
          ->setRequired(true)
          ->addValidator('NotEmpty', true)
          ->addValidator('EmailAddress', true)            
          ->addFilter('HTMLEntities')            
          ->addFilter('StringToLower')        
          ->addFilter('StringTrim');            
    
    // create text input for message body
    $message = new Zend_Form_Element_Textarea('message');
    $message->setLabel('contact-message')
            ->setOptions(array('rows' => '8','cols' => '36'))
            ->setRequired(true)
            ->addValidator('NotEmpty', true)
            ->addFilter('HTMLEntities')            
            ->addFilter('StringTrim');            
    
    // create captcha
    $captcha = new Zend_Form_Element_Captcha('captcha', array(
      'captcha' => array(
        'captcha' => 'Image',
        'wordLen' => 6,
        'timeout' => 300,
        'width'   => 400,
        'height'  => 100,
        'imgUrl'  => '/captcha',
        'imgDir'  => APPLICATION_PATH . '/../public/captcha',
        'font'    => APPLICATION_PATH . '/../public/fonts/LiberationSansRegular.ttf',
        )
    ));
    $captcha->setLabel('contact-verification');   


        // create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('contact-send-message')
               ->setOptions(array('class' => 'submit'));
                
    // attach elements to form
    $this->addElement($name)
         ->addElement($email)
         ->addElement($message)
         ->addElement($captcha)
         ->addElement($submit);
      
  }
}

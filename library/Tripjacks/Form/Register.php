<?php
class Tripjacks_Form_Register extends Zend_Form
{
  public function init()
  {
    // initialize form
    $this->setAction('/register/index')
         ->setMethod('post');
    
            // create text input for firstname
    $firstname = new Zend_Form_Element_Text('firstname');
    $firstname->setLabel('Firstname: ')
         ->setOptions(array('
             size' => '35',
             'id'=>'regfirstname',
             ))
         ->setRequired(true)
         ->addValidator('NotEmpty', true)
         ->addValidator('Alpha', true)            
         ->addFilter('HTMLEntities')            
         ->addFilter('StringTrim'); 
    
    
        // create text input for lastname 
    $lastname = new Zend_Form_Element_Text('lastname');
    $lastname->setLabel('Surname: ')
         ->setOptions(array('
             size' => '35',
             'id'=>'reglastname',
             ))
         ->setRequired(true)
         ->addValidator('NotEmpty', true)
         ->addValidator('Alpha', true)            
         ->addFilter('HTMLEntities')            
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
    
            // create text input for username 
    $username = new Zend_Form_Element_Text('username');
    $username->setLabel('Username:')
         ->setOptions(array(
             'size' => '35',
             'id'=>'regusername',
             ))
         ->setRequired(true)
         ->addValidator('NotEmpty', true)          
         ->addFilter('HTMLEntities')            
         ->addFilter('StringTrim'); 
    
    //create password input
    $pass = new Zend_Form_Element_Password('pass');
    $pass->setLabel('Password: ')
            ->setOptions(array(
                'id'=>'upass',
                'size'=>'35'
                ))
            ->addValidator('PasswordStrength')
            ->setRequired(true)
            ->addValidator('NotEmpty', true); 
    
            $dob = new ZendX_JQuery_Form_Element_DatePicker('dob',
            array('jQueryParams' => array('dateFormat' => 'dd/mm/yy')));
            $dob->setLabel('Date of birth: ');


    

    
    
    
    // create captcha
    $captcha = new Zend_Form_Element_Captcha('captcha', array(
      'captcha' => array(
        'captcha' => 'Image',
        'wordLen' => 6,
        'timeout' => 300,
        'width'   => 300,
        'height'  => 100,
        'imgUrl'  => '/captcha',
        'imgDir'  => APPLICATION_PATH . '/../public/captcha',
        'font'    => APPLICATION_PATH . '/../public/fonts/LiberationSansRegular.ttf',
        )
    ));
    $captcha->setLabel('Please varify the registration:');    
            
    // create submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Register')
           ->setOptions(array('class' => 'submit'));
                
    // attach elements to form
        $this->addElement($firstname)
                ->addElement($lastname)
                ->addElement($email)
                ->addElement($username)
                ->addElement($pass)
                ->addElement($dob)
                ->addElement($captcha)
                ->addElement($submit);
    }

}

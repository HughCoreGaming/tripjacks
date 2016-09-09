<?php
class Tripjacks_Form_LeaguesubUpdate extends Tripjacks_Form_LeaguesubCreate
{
  public function init()
  {
    // get parent form
    parent::init();
    
    // set form action (set to false for current URL)
    $this->setAction('/admin/specials/special/updatesub');

    // remove unwanted elements         
    $this->removeElement('Captcha');
    $this->removeDisplayGroup('verification');     
    $this->removeElement('images');
    $this->removeDisplayGroup('files');    
    
                         // create hidden input for item ID
    $subleagueid = new Zend_Form_Element_Hidden('subleagueid');
    $subleagueid->addValidator('Int')            
       ->addFilter('HtmlEntities')            
       ->addFilter('StringTrim'); 
       
        // create text input for name 
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('League Name:')
                ->setOptions(array('size' => '35'))
                ->setRequired(true)
                ->addFilter('HtmlEntities')
                ->addFilter('StringTrim');
        
           // create text input for name 
        $percent = new Zend_Form_Element_Text('percent');
        $percent->setLabel('Qualifying percent:')
                ->setOptions(array('size' => '35'))
                ->setRequired(true)
                ->addFilter('HtmlEntities')
                ->addFilter('StringTrim');
        
           // create text input for name 
        $price = new Zend_Form_Element_Text('price');
        $price->setLabel('Buy in Price:')
                ->setOptions(array('size' => '35'))
                ->setRequired(true)
                ->addFilter('HtmlEntities')
                ->addFilter('StringTrim');
        
        

        $startdate = new ZendX_JQuery_Form_Element_DatePicker('start_date',
                        array('jQueryParams' => array('dateFormat' => 'yy-mm-dd')));
        $startdate->setLabel('Start Date: ');

        $enddate = new ZendX_JQuery_Form_Element_DatePicker('end_date',
                        array('jQueryParams' => array('dateFormat' => 'yy-mm-dd')));
        $enddate->setLabel('End Date: ');
        
            // create select input for sex
    $sex= new Zend_Form_Element_Select('sex');
    $sex->setLabel('Gender: ')
          ->setRequired(true)               
          ->addFilter('HtmlEntities')            
          ->addFilter('StringTrim');            

      $sex->addMultiOption("either", "Either"); 
      $sex->addMultiOption("male", "Male");   
      $sex->addMultiOption("female", "Female"); 

        

        // create text input description
        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Description: ')
                ->setOptions(array('rows' => '8', 'cols' => '40'))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringTrim');




        // create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Update League')
                ->setOptions(array('class' => 'submit'));


        // attach elements to form
        $this->addElement($subleagueid)
                ->addElement($name)
                ->addElement($percent)
                ->addElement($price)
                ->addElement($startdate)
                ->addElement($enddate)
                ->addElement($sex)
                ->addElement($description)
                ->addElement($submit);
    }

}

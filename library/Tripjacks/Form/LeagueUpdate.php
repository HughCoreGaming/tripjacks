<?php
class Tripjacks_Form_LeagueUpdate extends Tripjacks_Form_LeagueCreate
{
  public function init()
  {
    // get parent form
    parent::init();
    
    // set form action (set to false for current URL)
    $this->setAction('/admin/specials/special/update');

    // remove unwanted elements         
    $this->removeElement('Captcha');
    $this->removeDisplayGroup('verification');     
    $this->removeElement('images');
    $this->removeDisplayGroup('files');    
    
           
               // create hidden input for item ID
    $leagueid = new Zend_Form_Element_Hidden('leagueid');
    $leagueid->addValidator('Int')            
       ->addFilter('HtmlEntities')            
       ->addFilter('StringTrim'); 

    // create text input for name 
    $name = new Zend_Form_Element_Text('name');
    $name->setLabel('League Name:')
             ->setOptions(array('size' => '35'))
             ->setRequired(true)           
             ->addFilter('HtmlEntities')            
             ->addFilter('StringTrim');  
        


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
        $this->addElement($leagueid)
        ->addElement($name)
        ->addElement($description)
        ->addElement($submit);
    }

}

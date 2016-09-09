<?php


class Tripjacks_Form_LeagueCreate extends Zend_Form {


     public function init()   {
        // initialize form
        $this->setAction('/admin/specials/special/create')
                ->setMethod('post');

        
          

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
        $submit->setLabel('Add League')
                ->setOptions(array('class' => 'submit'));
 

        // attach elements to form
        $this->addElement($name)
        ->addElement($description)
        ->addElement($submit);
    }

}

<?php
class Tripjacks_Form_Setup extends Zend_Form
{
  public function init()
  {
    // initialize form
    $this->setAction('/catalog/item/create')
         ->setMethod('post');
    
        // create hidden input for item ID
    $id = new Zend_Form_Element_Hidden('booking_playerid');
    $id->addValidator('Int')            
       ->addFilter('HtmlEntities')            
       ->addFilter('StringTrim'); 
         
    // create text input for name  
    $name = new Zend_Form_Element_Text('name');
    $name->setLabel('First name: ')
         ->setOptions(array(
             'size' => '35',
             'id'=>'name',
             ))
         ->setRequired(true)
         ->addValidator('NotEmpty', true)
         ->addValidator('Alpha', true)            
         ->addFilter('HTMLEntities')            
         ->addFilter('StringTrim'); 
    
    $playing = new Zend_Form_Element_Checkbox('playing');
    $playing->setLabel('Is player present?')
            ->setCheckedValue('Yes')
            ->setUncheckedValue('No');
         
           
 
    
    // attach element to form    
    $this->addElement($id)
         ->addElement($name)
         ->addElement($playing);
        
  }
}

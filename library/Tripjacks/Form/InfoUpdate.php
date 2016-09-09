<?php
class Tripjacks_Form_InfoUpdate extends Tripjacks_Form_InfoCreate
{
  public function init()
  {
    // get parent form
    parent::init();

    // initialize form
    $this->setAction('/admin/infos/info/update')
         ->setMethod('post');
    
        // create hidden input for item ID
    $infoid = new Zend_Form_Element_Hidden('infoid');
    $infoid->addValidator('Int')            
       ->addFilter('HtmlEntities')            
       ->addFilter('StringTrim');  
    
            // create text input for name 
    $title = new Zend_Form_Element_Text('title');
    $title->setLabel('Name:')
             ->setOptions(array('size' => '35'))
             ->setRequired(true)           
             ->addFilter('HtmlEntities')            
             ->addFilter('StringTrim'); 
    
    $info = new Zend_Form_Element_CKEditor('info');
    $info->setOptions(array('rows' => '8','cols' => '40', 'id'=>'info',));      

    // create submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Update')
           ->setOptions(array('class' => 'submit'));
         
    // attach elements to form    
    $this->addElement($infoid)
         ->addElement($title)
         ->addElement($info)
         ->addElement($submit);         
  }
}

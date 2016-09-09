<?php
class Tripjacks_Form_MainUpdate extends Tripjacks_Form_MainCreate
{
  public function init()
  {
    // get parent form
    parent::init();

    // initialize form
    $this->setAction('/admin/mains/main/update')
         ->setMethod('post');
    
        // create hidden input for item ID
    $mainid = new Zend_Form_Element_Hidden('mainid');
    $mainid->addValidator('Int')            
       ->addFilter('HtmlEntities')            
       ->addFilter('StringTrim');     
    
    $main = new Zend_Form_Element_CKEditor('body');
    $main->setOptions(array('rows' => '8','cols' => '40', 'id'=>'main',));      

    // create submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Update')
           ->setOptions(array('class' => 'submit'));
         
    // attach elements to form    
    $this->addElement($mainid)
         ->addElement($main)
         ->addElement($submit);         
  }
}

<?php
class Tripjacks_Form_LeagueUpload extends Zend_Form
{
  public function init()
  {
    // initialize form
    $this->setAction('/admin/specials/special/upload')
         ->setMethod('post');
    
        // create hidden input for item ID
    $venueid = new Zend_Form_Element_Hidden('leagueid');
    $venueid->addValidator('Int')            
       ->addFilter('HtmlEntities')            
       ->addFilter('StringTrim');   
  

        // create file input for item images
        $propic = new Zend_Form_Element_File('propic');
        $propic->addValidator('IsImage')
                ->addValidator('Size', false, '20000000000')
                ->addValidator('Extension', false, 'jpg,png,gif')
                ->addValidator('ImageSize', false, array(
                    'minwidth' => 100,
                    'minheight' => 100,
                    'maxwidth' => 800,
                    'maxheight' => 800
                ))
                ->setValueDisabled(true);

         
    // create submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Upload')
           ->setOptions(array('class' => 'submit'));
         
    // attach elements to form    
    $this->addElement($propic)
         ->addElement($venueid)
         ->addElement($submit);         
  }
}

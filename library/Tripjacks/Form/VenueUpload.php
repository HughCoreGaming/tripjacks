<?php
class Tripjacks_Form_VenueUpload extends Zend_Form
{
  public function init()
  {
    // initialize form
    $this->setAction('/admin/venues/venue/upload')
         ->setMethod('post');
  

        // create file input for item images
        $propic = new Zend_Form_Element_File('propic');
        $propic->addValidator('IsImage')
                ->addValidator('Size', false, '20000000000')
                ->addValidator('Extension', false, 'JPG,JPEG,jpeg,jpg,png,gif,GIF,PNG')
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
         ->addElement($submit);         
  }
}

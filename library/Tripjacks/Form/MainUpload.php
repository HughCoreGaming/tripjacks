<?php
class Tripjacks_Form_MainUpload extends Zend_Form
{
  public function init()
  {
    // initialize form
    $this->setAction('/admin/mains/main/upload')
         ->setMethod('post');
  

        // create file input for item images
        $pic = new Zend_Form_Element_File('pic');
        $pic->addValidator('IsImage')
                ->addValidator('Size', false, '20000000000')
                ->addValidator('Extension', false, 'JPG,JPEG,jpeg,jpg,png,gif,GIF,PNG')
                ->addValidator('ImageSize', false, array(
                    'minwidth' => 30,
                    'minheight' => 30,
                    'maxwidth' => 800,
                    'maxheight' => 800
                ))
                ->setValueDisabled(true);

         
    // create submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Upload')
           ->setOptions(array('class' => 'submit'));
         
    // attach elements to form    
    $this->addElement($pic)
         ->addElement($submit);         
  }
}

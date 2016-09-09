<?php
class Tripjacks_Form_InfoCreate extends Zend_Form
{
  public function init()
  {
    // initialize form
    $this->setAction('/admin/infos/info/create')
         ->setMethod('post')
            ->setAttrib('enctype', 'multipart/form-data'); 
    
        // create text input for name 
    $title = new Zend_Form_Element_Text('title');
    $title->setLabel('Name:')
             ->setOptions(array('size' => '35'))
             ->setRequired(true)           
             ->addFilter('HtmlEntities')            
             ->addFilter('StringTrim');  
    
    $info = new Zend_Form_Element_CKEditor('info');
    $info->setOptions(array('rows' => '8','cols' => '40', 'id'=>'info',))
            ->addDecorators(array(
    'FormElements'

));
    
          
    // attach elements to form->addElement($main)    
    $this->addElement($title)
         ->addElement($info);
    


      
    
        // create file input for item images
        $images = new Zend_Form_Element_File('images');
        $images->setMultiFile(3)
                ->addValidator('IsImage')
                ->addValidator('Size', false, '20000000000')
                ->addValidator('Extension', false, 'jpg,png,gif')
                ->addValidator('ImageSize', false, array(
                    'minwidth' => 100,
                    'minheight' => 100,
                    'maxwidth' => 800,
                    'maxheight' => 800
                ))
                ->setValueDisabled(true)
                         ->addDecorators(array(
        'FormElements',
        array('HtmlTag', array('tag' => 'div', 'id' => 'nothide')),
    ));

        // attach element to form    
    $this->addElement($images);
    


    // create submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Create')
           ->setOptions(array('class' => 'submit'))
            ->setOrder(40);
    
    
         
    // attach elements to form    
    $this->addElement($submit);  
    
        // create display group for file elements
    $this->addDisplayGroup(array('images'), 'files');
    $this->getDisplayGroup('files')
         ->setOrder(30)
         ->setLegend('Images');

    
    

  }
}

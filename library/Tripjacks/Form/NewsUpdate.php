<?php
class Tripjacks_Form_NewsUpdate extends Tripjacks_Form_NewsCreate
{
  public function init()
  {
    // get parent form
    parent::init();
    
    // set form action (set to false for current URL)
    $this->setAction('/admin/cms/news/update');
  
         
    // create hidden input for item ID
    $newsid = new Zend_Form_Element_Hidden('newsid');
    $newsid->addValidator('Int')            
       ->addFilter('HtmlEntities')            
       ->addFilter('StringTrim');            
  


    // create text input for name 
    $title = new Zend_Form_Element_Text('title');
    $title->setLabel('Name:')
             ->setOptions(array('size' => '25'))
             ->setRequired(true)           
             ->addFilter('HtmlEntities')            
             ->addFilter('StringTrim');  



        $date = new ZendX_JQuery_Form_Element_DatePicker('date',
            array('jQueryParams' => array('dateFormat' => 'yy-mm-dd')));
            $date->setLabel('Date: ');



        // create text input for message body
        $newsinfo = new Zend_Form_Element_Textarea('newsinfo');
        $newsinfo->setLabel('Information: ')
                ->setOptions(array('rows' => '8', 'cols' => '40'))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringTrim');



        // create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Update news feed')
                ->setOptions(array('class' => 'submit'));
 

        // attach elements to form
        $this->addElement($newsid)
                ->addElement($title)
                ->addElement($date)
                ->addElement($newsinfo)
                ->addElement($submit);
        

    }

}


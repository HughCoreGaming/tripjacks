<?php


class Tripjacks_Form_NewsCreate extends Zend_Form {


     public function init()   {
        // initialize form
        $this->setAction('admin/cms/news/create')
                ->setMethod('post');


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
        $submit->setLabel('Add news feed')
                ->setOptions(array('class' => 'submit'));
 

        // attach elements to form
        $this->addElement($title)
                ->addElement($date)
                ->addElement($newsinfo)
                ->addElement($submit);
        

    }

}


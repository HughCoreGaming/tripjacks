<?php

class Tripjacks_Form_PlayerCreatetemp extends Zend_Form {

    public function init() {
        // initialize form
        $this->setAction('admin/players/player/create')
                ->setMethod('post');


        // create text input for username 
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username:')
                ->setOptions(array('size' => '35'))
                ->setRequired(true)
                ->addValidator('Alnum')
                ->addValidator('Db_NoRecordExistsDoctrine', true, array('Users', 'username'))
                ->addFilter('HtmlEntities')
                ->addFilter('StringTrim');

        // create text input for password
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password:')
                ->setOptions(array('size' => '35'))
                ->setRequired(true)
                ->addFilter('HtmlEntities')
                ->addFilter('StringTrim');



        // create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Create')
                ->setOptions(array('class' => 'submit'));

        // attach elements to form
        $this->addElement($username)
                ->addElement($password)            
                ->addElement($submit);
    }

}
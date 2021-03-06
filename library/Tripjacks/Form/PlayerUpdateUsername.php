<?php

class Tripjacks_Form_PlayerUpdateUsername extends Tripjacks_Form_PlayerCreate {

    public function init() {
        // get parent form
        parent::init();

        // set form action (set to false for current URL)
        $this->setAction('/admin/players/player/updateusername');

        // remove unwanted elements        
        $this->removeElement('captcha');
        $this->removeDisplayGroup('verification');
        $this->removeElement('images');
        $this->removeDisplayGroup('files');

       

        
        // create hidden input for player ID
        $playerid = new Zend_Form_Element_Hidden('playerid');
        $playerid->addValidator('Int')
                ->addFilter('HtmlEntities')
                ->addFilter('StringTrim');
        
             // create text input for username 
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username:')
                ->setOptions(array('size' => '35'))
                ->setRequired(true)
                ->addValidator('Alnum')
                ->addValidator('Db_NoRecordExistsDoctrine', true, array('Users', 'username'))
                ->addFilter('HtmlEntities')
                ->addFilter('StringTrim');

 


        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Update Player')
                ->setOptions(array('class' => 'submit'));

        // attach elements to form
        $this->addElement($playerid)
                ->addElement($username)
                ->addElement($submit);
    }

}

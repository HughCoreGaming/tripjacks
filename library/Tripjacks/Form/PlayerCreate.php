<?php

class Tripjacks_Form_PlayerCreate extends Zend_Form {

    public function init() {
        // initialize form
        $this->setAction('/players/player/create')
                ->setMethod('post');


        $counties = array("Aberdeenshire" => "Aberdeenshire", "Angus/Forfarshire" => "Angus/Forfarshire", "Argyllshire" => "Argyllshire",
            "Ayrshire" => "Ayrshire", "Banffshire" => "Banffshire", "Bedfordshire" => "Bedfordshire", "Berkshire" => "Berkshire",
            "Berwickshire" => "Berwickshire", "Blaenau Gwent" => "Blaenau Gwent", "Bridgend" => "Bridgend",
            "Buckinghamshire" => "Buckinghamshire", "Buteshire" => "Buteshire", "Caerphilly" => "Caerphilly", "Caithness" => "Caithness",
            "Cambridgeshire" => "Cambridgeshire", "Cardiff" => "Cardiff", "Carmarthenshire" => "Carmarthenshire",
            "Ceredigion" => "Ceredigion", "Cheshire" => "Cheshire", "Clackmannanshire" => "Clackmannanshire",
            "Conwy" => "Conwy", "Cornwall" => "Cornwall", "Cromartyshire" => "Cromartyshire", "Cumberland" => "Cumberland",
            "Denbighshire" => "Denbighshire", "Derbyshire" => "Derbyshire", "Devon" => "Devon", "Dorset" => "Dorset",
            "Dumfriesshire" => "Dumfriesshire", "Dunbartonshire/Dumbartonshire" => "Dunbartonshire/Dumbartonshire",
            "Durham" => "Durham", "East Lothian/Haddingtonshire" => "East Lothian/Haddingtonshire", "Essex" => "Essex", "Fife" => "Fife",
            "Flintshire" => "Flintshire", "Gloucestershire" => "Gloucestershire", "Gwynedd" => "Gwynedd", "Hampshire" => "Hampshire",
            "Herefordshire" => "Herefordshire", "Hertfordshire" => "Hertfordshire", "Huntingdonshire" => "Huntingdonshire",
            "Inverness-shire" => "Inverness-shire", "Isle of Anglesey" => "Isle of Anglesey", "Kent" => "Kent",
            "Kincardineshire" => "Kincardineshire", "Kinross-shire" => "Kinross-shire", "Kirkcudbrightshire" => "Kirkcudbrightshire",
            "Lanarkshire" => "Lanarkshire", "Lancashire" => "Lancashire", "Leicestershire" => "Leicestershire",
            "Lincolnshire" => "Lincolnshire", "Merthyr Tydfil" => "Merthyr Tydfil", "Middlesex" => "Middlesex",
            "Midlothian/Edinburghshire" => "Midlothian/Edinburghshire", "Monmouthshire" => "Monmouthshire",
            "Morayshire" => "Morayshire", "Nairnshire" => "Nairnshire", "Neath Port Talbot" => "Neath Port Talbot",
            "Newport" => "Newport", "Norfolk" => "Norfolk", "Northamptonshire" => "Northamptonshire",
            "Northumberland" => "Northumberland", "Nottinghamshire" => "Nottinghamshire", "Orkney" => "Orkney",
            "Oxfordshire" => "Oxfordshire", "Peeblesshire" => "Peeblesshire", "Pembrokeshire" => "Pembrokeshire",
            "Perthshire" => "Perthshire", "Powys" => "Powys", "Renfrewshire" => "Renfrewshire",
            "Rhondda Cynon Taff" => "Rhondda Cynon Taff", "Ross-shire" => "Ross-shire", "Roxburghshire" => "Roxburghshire",
            "Rutland" => "Rutland", "Selkirkshire" => "Selkirkshire", "Shetland" => "Shetland", "Shropshire" => "Shropshire",
            "Somerset" => "Somerset", "Staffordshire" => "Staffordshire", "Stirlingshire" => "Stirlingshire", "Suffolk" => "Suffolk",
            "Surrey" => "Surrey", "Sussex" => "Sussex", "Sutherland" => "Sutherland", "Swansea" => "Swansea", "Torfaen" => "Torfaen", "Tyne and Wear" => "Tyne and Wear",
            "Vale of Glamorgan" => "Vale of Glamorgan", "Warwickshire" => "Warwickshire",
            "West Lothian/Linlithgowshire" => "West Lothian/Linlithgowshire", "Westmorland" => "Westmorland",
            "Wigtownshire" => "Wigtownshire", "Wiltshire" => "Wiltshire",
            "Worcestershire" => "Worcestershire", "Wrexham" => "Wrexham", "Yorkshire" => "Yorkshire");



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


        // create text input for firstname
        $firstname = new Zend_Form_Element_Text('firstname');
        $firstname->setLabel('Firstname: ')
                ->setOptions(array('
             size' => '35',
                    'id' => 'regfirstname',
                ))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addValidator('Alpha', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringTrim');


        // create text input for lastname 
        $lastname = new Zend_Form_Element_Text('lastname');
        $lastname->setLabel('Surname: ')
                ->setOptions(array('
             size' => '35',
                    'id' => 'reglastname',
                ))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addValidator('Alpha', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringTrim');


        // create text input for address
        $address = new Zend_Form_Element_Text('address');
        $address->setLabel('Address: ')
                ->setOptions(array('size' => '35'))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringTrim');

        // create text input for town
        $town = new Zend_Form_Element_Text('town');
        $town->setLabel('Village/Town/City: ')
                ->setOptions(array('size' => '35'))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringTrim');

        // create select input for county
        $county = new Zend_Form_Element_Select('county');
        $county->setLabel('County: ')
                ->setRequired(true)
                ->addFilter('HtmlEntities')
                ->addFilter('StringTrim');

        foreach ($counties as $id => $value) {
            $county->addMultiOption($id, $value);
        }

        // create text input for postcode
        $postcode = new Zend_Form_Element_Text('postcode');
        $postcode->setLabel('Postcode: ')
                ->setOptions(array('size' => '35'))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringTrim');

        // create text input for email address
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email address: ');
        $email->setOptions(array(
                    'size' => '35',
                    'id' => 'regemail'
                ))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addValidator('EmailAddress', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringToLower')
                ->addFilter('StringTrim');


        $dob = new Zend_Form_Element_Date('dob',
                        array('jQueryParams' => array('dateFormat' => 'dd/mm/yy')));
        $dob->setLabel('Date of birth: ')
                ->setOptions(array('
             size' => '35',
                    'id' => 'dob',
                ))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addValidator('AgeVarification', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringTrim');


        //create radio button for sex
        $sex = new Zend_Form_Element_Radio('sex');
        $sex->setLabel('Gender: ')
                ->setMultiOptions(array(
                    'male' => 'Male',
                    'female' => 'Female'
                ))
                ->setOptions(array('id' => 'sex'));




        // create captcha
        $captcha = new Zend_Form_Element_Captcha('captcha', array(
                    'captcha' => array(
                        'captcha' => 'Image',
                        'wordLen' => 6,
                        'timeout' => 300,
                        'width' => 300,
                        'height' => 100,
                        'imgUrl' => '/captcha',
                        'imgDir' => APPLICATION_PATH . '/../public/captcha',
                        'font' => APPLICATION_PATH . '/../public/fonts/LiberationSansRegular.ttf',
                    )
                ));
        $captcha->setLabel('Please verify the registration:');

        // create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Register')
                ->setOptions(array('class' => 'submit'));

        // attach elements to form
        $this->addElement($username)
                ->addElement($password)
                ->addElement($firstname)
                ->addElement($lastname)
                ->addElement($address)
                ->addElement($town)
                ->addElement($county)
                ->addElement($postcode)
                ->addElement($email)
                ->addElement($dob)
                ->addElement($sex)
                ->addElement($captcha)
                ->addElement($submit);
    }

}
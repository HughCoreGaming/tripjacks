<?php

class Plugin_PublicNavigation extends Zend_Controller_Plugin_Abstract {

    function preDispatch(Zend_Controller_Request_Abstract $request) {
        $view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;

        //load initial navigation from XML
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/public_nav.xml', 'nav');
        $container = new Zend_Navigation($config);


        //get database data
        $q = Doctrine_Query::create()
                ->from('Tripjacks_Model_Venue v');
        $result = $q->fetchArray();


        //get root page
        $submenu = $navigation->findOneBy('label', 'Venues');


        foreach ($result as $i => $row) {

            $submenu->addPage(Zend_Navigation_Page::factory(array(
                        'label' => $row['name'],
                        'action' => 'display',
                        'controller' => 'venue',
                        'module' => 'venues',
                        'class' => 'subnav',
                        'params' => array('venueid' => $row['venueid'])
                    )));
        }

        //pass container to view
        $view->navigation($container);
    }

}


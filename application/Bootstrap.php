<?php


class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
   
    
     protected function _initDoctrine() {
        require_once 'Doctrine/Doctrine.php';
        $this->getApplication()
                ->getAutoloader()
                ->pushAutoloader(array('Doctrine', 'autoload'), 'Doctrine');

        $manager = Doctrine_Manager::getInstance();
        $manager->setAttribute(
                Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_CONSERVATIVE
        );

        $config = $this->getOption('doctrine');
        $conn = Doctrine_Manager::connection($config['dsn'], 'doctrine');

        return $conn;
    }

    protected function _initViewHelpers() {
        $view = new Zend_View();
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();

        $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
    }


    protected function _initRoutes() {
        
            $this->bootstrap('frontController');
            $frontController = $this->getResource('frontController');
            $router = $frontController->getRouter();


            $router->addRoute(
                    'admin-games-ajaxinsertresults', new Zend_Controller_Router_Route('admin/games/game/ajaxinsertresults', array('module' => 'games', 'controller' => 'admin.game', 'action' => 'ajaxinsertresults'))
            );


            $router->addRoute(
                    'admin-games-playerdetails', new Zend_Controller_Router_Route('admin/games/game/playerdetails', array('module' => 'games', 'controller' => 'admin.game', 'action' => 'playerdetails'))
            );

            $router->addRoute(
                    'admin-games-getpic', new Zend_Controller_Router_Route('admin/games/game/getpic', array('module' => 'games', 'controller' => 'admin.game', 'action' => 'getpic'))
            );


            $router->addRoute(
                    'admin-bookings-search', new Zend_Controller_Router_Route('admin/bookings/booking/search/:q', array('module' => 'bookings', 'controller' => 'admin.booking', 'action' => 'search', 'q' => NULL))
            );

            $router->addRoute(
                    'admin-bookings-skirmishsetup', new Zend_Controller_Router_Route('admin/bookings/booking/skirmishsetup/:date/:venueid', array('module' => 'bookings', 'controller' => 'admin.booking', 'action' => 'skirmishsetup', 'date' => NULL, 'venueid' => NULL))
            );

            $router->addRoute(
                    'admin-bookings-insert', new Zend_Controller_Router_Route('admin/bookings/booking/insert/:id/:bookingid/:venueid', array('module' => 'bookings', 'controller' => 'admin.booking', 'action' => 'insert', 'id' => NULL, 'bookingid' => NULL, 'venueid' => NULL))
            );



            $router->addRoute(
                    'venues-bookingindex', new Zend_Controller_Router_Route('/venues/venue/bookingindex/:bookingid', array('module' => 'venues', 'controller' => 'venue', 'action' => 'bookingindex', 'bookingid' => NULL))
            );

            $router->addRoute(
                    'venues-bookingindex2', new Zend_Controller_Router_Route('/venues/venue/bookingindex/:bookingid/:trigger', array('module' => 'venues', 'controller' => 'venue', 'action' => 'bookingindex', 'bookingid' => NULL, 'trigger' => NULL))
            );


            $router->addRoute(
                    'admin-games-display', new Zend_Controller_Router_Route('/admin/games/game/display/:bookingid/:venueid/:times', array('module' => 'games', 'controller' => 'admin.game', 'action' => 'display', 'bookingid' => NULL, 'venueid' => NULL, 'times' => NULL))
            );

            $router->addRoute(
                    'admin-games-index', new Zend_Controller_Router_Route('admin/games/game/index/:bookingid/:venueid', array('module' => 'games', 'controller' => 'admin.game', 'action' => 'index', 'bookingid' => NULL, 'venueid' => NULL))
            );



            $router->addRoute(
                    'admin-games-presentupdate', new Zend_Controller_Router_Route('admin/games/game/presentupdate/:bookingid/:venueid', array('module' => 'games', 'controller' => 'admin.game', 'action' => 'presentupdate', 'bookingid' => NULL, 'venueid' => NULL))
            );

            $router->addRoute(
                    'admin-games-update', new Zend_Controller_Router_Route('admin/games/game/update/:bookingid', array('module' => 'games', 'controller' => 'admin.game', 'action' => 'update', 'bookingid' => NULL))
            );




            $router->addRoute(
                    'bookings-gamedisplay', new Zend_Controller_Router_Route('/bookings/booking/gamedisplay/:venueid', array('module' => 'bookings', 'controller' => 'booking', 'action' => 'bookingdisplay', 'venueid' => NULL))
            );


            $router->addRoute(
                    'bookings-bookingdisplay', new Zend_Controller_Router_Route('/bookings/booking/bookingdisplay/:bookingid/:venueid', array('module' => 'bookings', 'controller' => 'booking', 'action' => 'bookingdisplay', 'bookingid' => NULL, 'venueid' => NULL))
            );



            $router->addRoute(
                    'admin-bookings-gameindex', new Zend_Controller_Router_Route('/admin/bookings/booking/gameindex/:venueid', array('module' => 'bookings', 'controller' => 'admin.booking', 'action' => 'gameindex', 'venueid' => NULL))
            );



            $router->addRoute(
                    'admin-bookings-bookingindex', new Zend_Controller_Router_Route('admin/bookings/booking/bookingindex/:bookingid/:venueid', array('module' => 'bookings', 'controller' => 'admin.booking', 'action' => 'bookingindex', 'bookingid' => NULL, 'venueid' => NULL))
            );



            $router->addRoute(
                    'admin-bookings-bookupdate', new Zend_Controller_Router_Route('admin/bookings/booking/bookupdate/:bookingid/:venueid', array('module' => 'bookings', 'controller' => 'admin.booking', 'action' => 'bookupdate', 'bookingid' => NULL, 'venueid' => NULL))
            );
            $router->addRoute(
                    'admin-bookings-buyupdate', new Zend_Controller_Router_Route('admin/bookings/booking/buyupdate/:bookingid/:venueid', array('module' => 'bookings', 'controller' => 'admin.booking', 'action' => 'buyupdate', 'bookingid' => NULL, 'venueid' => NULL))
            );
            $router->addRoute(
                    'admin-bookings-buyleagueupdate', new Zend_Controller_Router_Route('admin/bookings/booking/buyleagueupdate/:subleagueid/:playerid', array('module' => 'bookings', 'controller' => 'admin.booking', 'action' => 'buyleagueupdate', 'subleagueid' => NULL, 'playerid' => NULL))
            );

            $router->addRoute(
                    'admin-leagues-index', new Zend_Controller_Router_Route('admin/leagues/league/index', array('module' => 'leagues', 'controller' => 'admin.league', 'action' => 'index'))
            );

            $router->addRoute(
                    'leagues-index', new Zend_Controller_Router_Route('/leagues/league/index/:id', array('module' => 'leagues', 'controller' => 'admin.league', 'action' => 'index', 'id' => NULL))
            );

            $router->addRoute(
                    'admin-venues-uploadspecial', new Zend_Controller_Router_Route('/admin/venues/venue/uploadspecial/:id', array('module' => 'venues', 'controller' => 'admin.venue', 'action' => 'uploadspecial', 'id' => NULL))
            );

            $router->addRoute(
                    'bookings-gamedisplay', new Zend_Controller_Router_Route('/bookings/booking/gamedisplay/:venueid', array('module' => 'bookings', 'controller' => 'booking', 'action' => 'bookingdisplay', 'venueid' => NULL))
            );



            $router->addRoute(
                    'infos-display', new Zend_Controller_Router_Route('/infos/info/display/:id', array('module' => 'infos', 'controller' => 'info', 'action' => 'display', 'id' => NULL))
            );

            $router->addRoute(
                    'venues-index', new Zend_Controller_Router_Route('/venues/venue/index/:venueid', array('module' => 'venues', 'controller' => 'venue', 'action' => 'index', 'venueid' => NULL))
            );
            $router->addRoute(
                    'venues-gameindex', new Zend_Controller_Router_Route('/venues/venue/gameindex/:venueid', array('module' => 'venues', 'controller' => 'venue', 'action' => 'gameindex', 'venueid' => NULL))
            );
        }

        protected function _initNavigation() {


            $q = Doctrine_Query::create()
                    ->from('Tripjacks_Model_Venue v');
            $result = $q->fetchArray();



            $this->bootstrap("layout");
            $layout = $this->getResource('layout');
            $view = $layout->getView();

            $navigation = new Zend_Navigation();



            // add page by giving a page instance
            $navigation->addPage(Zend_Navigation_Page::factory(array(
                        'label' => 'Home',
                        'uri' => '/home',
                        'class' => 'mainnav'
                    )));

            $navigation->addPage(Zend_Navigation_Page::factory(array(
                        'label' => 'Contact Us',
                        'uri' => '/contactus',
                        'class' => 'mainnav'
                    )));

            $navigation->addPage(Zend_Navigation_Page::factory(array(
                        'label' => 'Leagues',
                        'uri' => '/leagues',
                        'class' => 'mainnav'
                    )));
            $navigation->addPage(Zend_Navigation_Page::factory(array(
                        'label' => 'Venues',
                        'action' => 'index',
                        'controller' => 'search',
                        'module' => 'blog',
                        'class' => 'mainnav'
                    )));



            $submenu = $navigation->findOneBy('label', 'Leagues');


            foreach ($result as $i => $row) {

                $submenu->addPage(Zend_Navigation_Page::factory(array(
                            'label' => $row['name'],
                            'action' => 'index',
                            'controller' => 'league',
                            'module' => 'leagues',
                            'route' => 'leagues-index',
                            'class' => 'subnav',
                            'params' => array('venueid' => $row['venueid'])
                        )));
            }



            $submenu = $navigation->findOneBy('label', 'Venues');


            foreach ($result as $i => $row) {

                $submenu->addPage(Zend_Navigation_Page::factory(array(
                            'label' => $row['name'],
                            'action' => 'gameindex',
                            'controller' => 'venue',
                            'module' => 'venues',
                            'route' => 'venues-gameindex',
                            'class' => 'subnav',
                            'params' => array('venueid' => $row['venueid'])
                        )));
            }




            $view->navigation($navigation);
        }

}


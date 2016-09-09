<?php
class StaticAdsController extends Zend_Controller_Action
{
  public function init()
  {
   $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/center.css');
  }
  
    // action to handle admin URLs
  public function preDispatch() 
  {

    $this->_helper->layout->setLayout('ads');          

    }


    // display static views
    public function displayAction()
    {
                
      $page = $this->getRequest()->getParam('page');
		  if (file_exists($this->view->getScriptPath(null) . "/" . $this->getRequest()->getControllerName() . "/$page." . $this->viewSuffix)) {
        $this->render($page);
      } else {
        throw new Zend_Controller_Action_Exception('Page not found', 404);
      }
    }    
}


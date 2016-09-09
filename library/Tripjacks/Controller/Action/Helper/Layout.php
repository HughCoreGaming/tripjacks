<?php
// code credited to Ryan Mauger, technical editor 
class Tripjacks_Controller_Action_Helper_Layout extends Zend_Controller_Action_Helper_Abstract
{

  // check current request and set active page
  public function preDispatch()
  {
    $this->getContainer()
         ->findBy('uri', $this->getRequest()->getRequestUri())
         ->active = true;
  }
  
}

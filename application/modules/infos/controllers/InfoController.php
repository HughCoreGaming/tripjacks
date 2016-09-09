<?php
class Infos_InfoController extends Zend_Controller_Action
{  
  public function init() 
  {
    $this->view->doctype('XHTML1_STRICT');
    $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/center.css');
  }

  // action to display an info page
    public function displayAction() {
        // set filters and validators for GET input
        $filters = array(
            'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
        );
        $validators = array(
            'id' => array('NotEmpty', 'Int')
        );
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());

        // test if input is valid
        // retrieve requested record
        // attach to view
        if ($input->isValid()) {

            $q = Doctrine_Query::create()
                    ->from('Tripjacks_Model_Info i')
                    ->where('i.infoid = ?', $input->id);
            $result = $q->fetchArray();


            if (count($result) == 1) {
                $this->view->info = $result[0];
            } else {
                throw new Zend_Controller_Action_Exception('Page not found', 404);
            }
        } else {
            throw new Zend_Controller_Action_Exception('Invalid input');
        }
    }

    // action to display list of info
  public function indexAction()
  {
    $q = Doctrine_Query::create()
          ->from('Tripjacks_Model_Info i');
    $result = $q->fetchArray();
    $this->view->records = $result; 
  }


  
}
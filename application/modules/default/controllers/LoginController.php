<?php
class LoginController extends Zend_Controller_Action
{
  public function init()
  {
    $this->view->doctype('XHTML1_STRICT');
    $this->_helper->layout->setLayout('admin');     
    $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/center.css');
  }

  
  
  // login action
  public function loginAction()
  { 
    $form = new Tripjacks_Form_Login;
    $this->view->form = $form;    
    
    // check for valid input
    // authenticate using adapter
    // persist user record to session
    // redirect to original request URL if present
    if ($this->getRequest()->isPost()) {
      if ($form->isValid($this->getRequest()->getPost())) {
        $values = $form->getValues();

        $username = addslashes($values['username']);
        $password = addslashes($values['password']);    
        $access="admin";
        
        $adapter = new Tripjacks_Auth_Adapter_Doctrine(
          $username, $password, $access
        );

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
 

        if ($result->isValid()) {
          $session = new Zend_Session_Namespace('tripjacks.auth');
          $session->user = $adapter->getResultArray('password');
          $userid = $session->user['userid'];
          $access = $session->user['access'];
          
          $user_info = new Tripjacks_Model_Player;
          if ($access == "player"){
              $name = $user_info->getPlayerInfoWithoutUsers($userid);
              $session->name = $name[0]['player'];             
          }elseif ($access == "player"){
              $name = $user_info->getVenueInfoWithoutUsers($userid);
              $session->name = $name[0]['name'];
          }
     
       
          if (isset($session->requestURL)) {
            $url = $session->requestURL;
            unset($session->requestURL);
            $this->_redirect($url);  
          } else {
            $this->_helper->getHelper('FlashMessenger')
                          ->addMessage('You were successfully logged in.');
            $this->_redirect('/home');
          }
        } else {
          $this->view->message = 'You could not be logged in. Please try again.';          
        }        
      }
    }
  }
  
  
  //request password reset action

    public function requestAction() {
        

        $form = new Tripjacks_Form_Request();
        $this->view->form = $form;

        // test for valid input
        // if valid, populate model
        // assign default values for some fields
        // save to database
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $values = $form->getValues();

                $user_info = new Tripjacks_Model_Player;
                $users_for_salt = new Tripjacks_Model_Users;
                $user = $users_for_salt->getUserid($values['username']);

                if ($user['access'] == "player") {
                    $player_info = $user_info->getPlayerInfoWithoutUsers($user['userid']);
                    $email = $player_info[0]['email'];
                    $name = $player_info[0]['firstname'];
                }
                if ($user['access'] == "venue") {
                    $venue_info = $user_info->getVenueInfoWithoutUsers($user['userid']);
                    $email = $venue_info[0]['email'];
                    $name = $player_info[0]['name'];
                }


                if ($email == $values['email']) {
                    
                    //make sure urid is unique

                    $rand = $users_for_salt->generate_salt2();
                    $count = $venue_info = $user_info->countId($rand);
                
                 while ($count >= 1) {

                        $rand = $users_for_salt->generate_salt2();
                        $count = $venue_info = $user_info->countId($rand);
                    }


                    //Insert to User reset table
                    $UserReset = new Tripjacks_Model_UserReset;
                    $UserReset->urid = $rand;
                    $UserReset->userid = $user['userid'];
                    $UserReset->save();

                    //content of email
                    
                    $bodytext = "";
        
                    
                    $bodytext = "Hi $name Please click this address to reset your password: www.tripjackspaco.com/reset/$rand";
                    
                    //email link to customer

                    $mail = new Zend_Mail();
                    $mail->setBodyHtml($bodytext);        
                    $mail->setReplyTo('info@tripjackspaco.com', 'TripjacksPACO');
                    $mail->setFrom('info@tripjackspaco.com');
                    $mail->addTo($email);
                    $mail->setSubject('TripjacksPACO Reset Request');
                    $mail->send();

                    $this->_helper->getHelper('FlashMessenger')->addMessage('Your request has been sent. Please reset by 30 mins');
                    $this->_redirect('/players/player/success');
                } else {
                    $this->_helper->getHelper('FlashMessenger')->addMessage('This email does not match the username! Request denied.');
                    $this->_redirect('/players/player/success');
                }
            }
        }
    }
  

  public function resetAction() {



        $form = new Tripjacks_Form_Reset();
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            // if POST request
            // test if input is valid
            // retrieve current record
            // update values and replace in database
            $postData = $this->getRequest()->getPost();

            if ($form->isValid($postData)) {
                $input = $form->getValues();
                if ($input['password'] == $input['redopassword']) {

                    // Get a salt using our function 
                    $users_for_salt = new Tripjacks_Model_Users;
                    $rand = $users_for_salt->generate_salt();

                    // Now encrypt the password using that salt
                    $encrypted = md5(md5($input['password']) . $rand);

                    $users = Doctrine::getTable('Tripjacks_Model_Users')->find($input['userid']);
                    $users->password = $encrypted;
                    $users->rand = $rand;
                    $users->save();
                    $userid = $users->userid;


                    $this->_helper->getHelper('FlashMessenger')->addMessage('Your password was reset sucessfully.');
                    $this->_redirect('/login/success');
                } else {
                    $this->view->message = 'Passwords do not match! Please redo.';
                }
            }
        } else {

            $filters = array(
                'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
            );
            $validators = array(
                'id' => array('NotEmpty', 'Int')
            );

            $input = new Zend_Filter_Input($filters, $validators);
            $input->setData($this->getRequest()->getParams());
            if ($input->isValid()) {


                $q = Doctrine_Query::create()
                        ->from('Tripjacks_Model_UserReset ur')
                        ->where('ur.urid = ?', $input->id);
                $result = $q->fetchArray();

                $timestamp = strtotime($result[0]['expiration']);
                $deltaTimestamp = strtotime("+30 minutes", $timestamp);
                $expiration = date('Y-m-d H:i:s', $deltaTimestamp);


                if (count($result) == 1 && $expiration > date('Y-m-d H:i:s')) {
                    // allow reset
                    $form = new Tripjacks_Form_Reset();
                    $this->view->form = $form;
                    $this->view->form->populate($result[0]);
                } else {
                    throw new Zend_Controller_Action_Exception('Page not found', 404);
                }
            } else {
                throw new Zend_Controller_Action_Exception('Invalid input');
            }
        }
    }
    

    public function successAction() {
        if ($this->_helper->getHelper('FlashMessenger')->getMessages()) {
            $this->view->messages = $this->_helper
                    ->getHelper('FlashMessenger')
                    ->getMessages();
        } else {
            $this->_redirect('/');
        }
    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
        $this->_redirect('/admin/login');
    }

}


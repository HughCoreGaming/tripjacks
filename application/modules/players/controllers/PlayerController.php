<?php
class Players_PlayerController extends Zend_Controller_Action
{  
  public function init() 
  {
    $this->view->doctype('XHTML1_STRICT');
    $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/center.css');
  }

  // action to display a cms news
  public function displayAction()
  {
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
            ->from('Tripjacks_Model_News i')
            ->where('i.newsid = ?', $input->id);

      $result = $q->fetchArray();
      if (count($result) == 1) {
        $this->view->news = $result[0];                
      } else {
        throw new Zend_Controller_Action_Exception('Page not found', 404);        
      }
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }

    public function createAction() {
        $form = new Tripjacks_Form_PlayerCreate;
        $this->view->form = $form;

        // test for valid input
        // if valid, populate model
        // assign default values for some fields
        // save to database
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $values = $form->getValues();
                
                
               // Get a salt using our function 
               $users_for_salt = new Tripjacks_Model_Users;
               $rand = $users_for_salt->generate_salt();

                // Now encrypt the password using that salt
   	        $encrypted = md5(md5($values['password']).$rand);

                $users = new Tripjacks_Model_Users;
                $users->username = $values['username'];
                $users->password = $encrypted;
                $users->access = "player";
                $users->rand = $rand;
                $users->save();
                $userid = $users->userid;
                             
                $the_player = new Tripjacks_Model_Player;
                $join_firstlast = array($values['firstname'], $values['lastname']);
                $player = join(" ", $join_firstlast);
                $the_player->firstname = $values['firstname'];
                $the_player->lastname = $values['lastname'];
                $the_player->address=$input["address"];
                $the_player->town=$input["town"];
                $the_player->county=$input["county"];
                $the_player->postcode=$input["postcode"];
                $the_player->player = $player;
                $the_player->email = $values['email'];
                $the_player->dob = $values['dob'];
                $the_player->sex = $values['sex'];
                $the_player->propic = 'nopic.JPG';
                $the_player->regdate = date("Y-m-d");
                $the_player->userid = $userid;
                $the_player->save();
                $playerid = $the_player->playerid;
                                
                     //member number generator
                $gen_functions = new Tripjacks_Model_Users;
                $rand_chr = $gen_functions->randLetter();
                $rand_chr2 = $gen_functions->randLetter();
                $rand_int = $gen_functions->generate_salt();
                
               
                $arr = array($rand_chr, $rand_chr2, $rand_chr, $rand_int);
                $newmemberno = join("", $arr);
                
                $player_count=$gen_functions->player_memberno_exists($newmemberno);
                $venue_count=$gen_functions->venue_memberno_exists($newmemberno);
                
                while($player_count>=1&&$venue_count>=1){
                    
                $player_count=$gen_functions->player_memberno_exists($newmemberno);
                $venue_count=$gen_functions->venue_memberno_exists($newmemberno);
                    
                 
                //member number generator
                $gen_functions = new Tripjacks_Model_Users;
                $rand_chr = $gen_functions->randLetter();
                $rand_chr2 = $gen_functions->randLetter();
                $rand_int = $gen_functions->generate_salt();
                
               
                $arr = array($rand_chr, $rand_chr2, $rand_chr, $rand_int);
                $newmemberno = join("", $arr);
                     
                    
                }
  
                $the_player_memberno = Doctrine::getTable('Tripjacks_Model_Player')->find($playerid);   
                $the_player_memberno->memberno = $newmemberno;
                $the_player_memberno->save();
                
                $json_member_no = json_encode($newmemberno);
                
                        $ip = $_SERVER['REMOTE_ADDR'];
                $content = "Add new player has registered:

  

            His name is: '" . $player . "'  and  IP address is:  .$ip.

            ";

        $mail = new Zend_Mail();
        $mail->setBodyText($content);
        $mail->setReplyTo('dippydoon100@yahoo', 'TripjacksPACO');
        $mail->setFrom($values['email'], $player);
        $mail->addTo('dippydoon100@yahoo.com'); 
        $mail->setSubject('New player registered');
        $mail->send();
        
        
        $message = "
Hi, '".$player."' 
<br>

Welcome to TripjacksPACo Please keep your member number safe. 
The member number is there to identify yourself. If you forget
to book, take your member number to a game and we can use it 
to book you in.
<br>

<b>Memebr Nmuber: '".$newmemberno."' </b>
<br>    

Thanks
<br>

The team at TripjacksPACo
<br>
*********************************************************
<br>
From Tripjackspaco
<br>
Email Us: info@tripjackspaco.com
<br>
*********************************************************

<br>
";


         $mail = new Zend_Mail();
        $mail->setBodyHtml($message);
        $mail->setReplyTo('info@tripjackspaco.com', 'TripjacksPACO');
        $mail->setFrom('info@tripjackspaco.com', 'TripjackPACo Team');
        $mail->addTo($values['email']); 
        $mail->setSubject('Welcome to TripjackPACo');
        $mail->send();

                $this->_helper->getHelper('FlashMessenger')->addMessage('Thanks you for Registering! Here is your Member Number #' . $newmemberno .'. Please login to view your profile.');
                $this->_helper->getHelper('FlashMessenger')->addMessage($json_member_no);
              
                $this->_redirect('/players/player/success');
            }
        }
    }

    public function successAction()
  {

    if ($this->_helper->getHelper('FlashMessenger')->getMessages()) {
      $this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();    
    } else {
      $this->_redirect('/');    
    } 
  }  
  
}
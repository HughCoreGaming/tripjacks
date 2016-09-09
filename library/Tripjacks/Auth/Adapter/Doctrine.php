<?php
class Tripjacks_Auth_Adapter_Doctrine implements Zend_Auth_Adapter_Interface
{
  // array containing authenticated user record
  protected $_resultArray;
  
  // constructor
  // accepts username and password    
  public function __construct($username, $password, $access)
  {
    $this->username = $username;
    $this->password = $password;
    $this->access = $access;
  }

  // main authentication method
    // queries database for match to authentication credentials
    // returns Zend_Auth_Result with success/failure code
    public function authenticate() {

        if ($this->access == "admin") {

            // Try and get the salt from the database using the username
            $query = Doctrine_Query::create()
                    ->from('Tripjacks_Model_Users u')
                    ->where('u.username = ?', $this->username)
                    ->limit(1);
            $res = $query->fetchArray();

            $encrypted_pass = md5(md5($this->password) . $res[0]['rand']);

            $q = Doctrine_Query::create()
                    ->from('Tripjacks_Model_Users u')
                    ->where('u.Username = ? AND u.Password = ?', array($this->username, $encrypted_pass)
            );

            $result = $q->fetchArray();
        } elseif ($this->access == "client") {

            // Try and get the salt from the database using the username
            $query = Doctrine_Query::create()
                    ->from('Tripjacks_Model_Users u')
                    ->where('u.username = ?', $this->username)
                    ->limit(1);
            $res = $query->fetchArray();

            $encrypted_pass = md5(md5($this->password) . $res['rand']);

            $q = Doctrine_Query::create()
                    ->from('Tripjacks_Model_Users u')
                    ->where('u.Username = ? AND u.Password = ?', array($this->username, $encrypted_pass)
            );

            $result = $q->fetchArray();
        } elseif ($this->access == "player") {

            // Try and get the salt from the database using the username
            $query = Doctrine_Query::create()
                    ->from('Tripjacks_Model_Users u')
                    ->where('u.username = ?', $this->username)
                    ->limit(1);
            $res = $query->fetchArray();

            $encrypted_pass = md5(md5($this->password) . $res['rand']);

            $q = Doctrine_Query::create()
                    ->from('Tripjacks_Model_Users u')
                    ->where('u.Username = ? AND u.Password = ?', array($this->username, $encrypted_pass)
            );

            $result = $q->fetchArray();
        }
 
    

    if (count($result) == 1) {
      $this->_resultArray = $result[0];
      return new Zend_Auth_Result(
        Zend_Auth_Result::SUCCESS, array($result[0]), array());
    } else {
      return new Zend_Auth_Result(
        Zend_Auth_Result::FAILURE, null, 
          array('Authentication unsuccessful')
      );      
    }
  }
  
  
  public function getAccess(){
      
      return $this->access;
      
  }
  
  // returns result array representing authenticated user record
  // excludes specified user record fields as needed
  public function getResultArray($excludeFields = null)
  {
    if (!$this->_resultArray) {
      return false;
    } 

    if ($excludeFields != null) {
      $excludeFields = (array)$excludeFields;
      foreach ($this->_resultArray as $key => $value) {
        if (!in_array($key, $excludeFields)) {  
          $returnArray[$key] = $value;  
        }
      }
      return $returnArray;      
    } else {
      return $this->_resultArray;        
    }      
  }
}

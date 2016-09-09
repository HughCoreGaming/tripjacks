<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: NoRecordExists.php 24594 2012-01-05 21:27:01Z matthew $
 */

/**
 * @see Zend_Validate_Db_Abstract
 */
require_once 'Zend/Validate/Db/Abstract.php';

/**
 * Confirms a record does not exist in a table.
 *
 * @category   Zend
 * @package    Zend_Validate
 * @uses       Zend_Validate_Db_Abstract
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Validate_Db_NoRecordExistsDoctrine extends Zend_Validate_Db_Abstract
{
   protected $_table;
    protected $_field;

    const OK = '';

    protected $_messageTemplates = array(
        self::OK => "'%value%' already exists, please try another!"
    );

    public function __construct($table, $field) {
        
        $route = "Tripjacks_Model_";
        $the_info = array($route, $table);
        $class_of_table = join($the_info);
        
        if(is_null(Doctrine::getTable($class_of_table)))
            return null;

        if(!Doctrine::getTable($class_of_table)->hasColumn($field))
            return null;

        $this->_table = Doctrine::getTable($class_of_table);
        $this->_field = $field;
    }

    public function isValid($value)
    {

        $this->_setValue($value);

        $funcName = 'findBy' . $this->_field;

  if(count($this->_table->$funcName($value))>0) {
            $this->_error(null);
            return false;
        }

        return true;

    }
}

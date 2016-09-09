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
 * @version    $Id: Alpha.php 24594 2012-01-05 21:27:01Z matthew $
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Validate_AgeVarification extends Zend_Validate_Abstract{
    
    const OK = '';
    
    protected $_messageTemplates = array(
        self::OK => "You must be 18 or over to register!"
    );
    
        public function isValid($value) {
        $birth = new Zend_Date($value);
        $today = new Zend_Date();
        $diff = $today->sub($birth)->toValue();
        $age = floor($diff / 3600 / 24 / 365);
        if ($age < 18) {
             $this->_error(self::OK);
            return false;
        }
        return true;
    }
    
}

?>

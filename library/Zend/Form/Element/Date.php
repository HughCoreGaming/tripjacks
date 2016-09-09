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
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @version    $Id: Exception.php 24594 2012-01-05 21:27:01Z matthew $
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/** Zend_Form_Element_Xhtml */
require_once 'Zend/Form/Element/Xhtml.php';

/**
 * Exception for Zend_Form component.
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


class Zend_Form_Element_Date extends Zend_Form_Element_Xhtml

{

    public $helper = 'formDate';



    public function isValid ($value, $context = null)

    {

        if (is_array($value)) {

            $value = $value['year'] . '-' .

                $value['month'] . '-' .

                $value['day'];

            

            if($value == '--') {

                $value = null;

            }

        }



        return parent::isValid($value, $context);

    }



    public function getValue()

    {

        if(is_array($this->_value)) {

            $value = $this->_value['year'] . '-' .

                $this->_value['month'] . '-' .

                $this->_value['day'];



            if($value == '--') {

                $value = null;

            }

            $this->setValue($value);

        }



        return parent::getValue();

    }



}
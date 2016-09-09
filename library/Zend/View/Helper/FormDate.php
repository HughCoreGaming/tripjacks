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
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: FormHidden.php 24594 2012-01-05 21:27:01Z matthew $
 */


/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';


/**
 * Helper to generate a "hidden" element
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_View_Helper_FormDate extends Zend_View_Helper_FormElement

{

    public function formDate ($name, $value = null, $attribs = null)

    {

        // separate value into day, month and year

        $day = '';

        $month = '';

        $year = '';

        if (is_array($value)) {

            $day = $value['day'];

            $month = $value['month'];

            $year = $value['year'];

        } elseif (strtotime($value)) {

            list($year, $month, $day) = explode('-', date('Y-m-d', strtotime($value)));

        }



        // build select options

        $dayAttribs = isset($attribs['dayAttribs']) ? $attribs['dayAttribs'] : array();

        $monthAttribs = isset($attribs['monthAttribs']) ? $attribs['monthAttribs'] : array();

        $yearAttribs = isset($attribs['yearAttribs']) ? $attribs['yearAttribs'] : array();



        $dayMultiOptions = array('' => '');

        for ($i = 1; $i < 32; $i ++)

        {

            $index = str_pad($i, 2, '0', STR_PAD_LEFT);

            $dayMultiOptions[$index] = str_pad($i, 2, '0', STR_PAD_LEFT);

        }

        $monthMultiOptions = array('' => '');

        for ($i = 1; $i < 13; $i ++)

        {

            $index = str_pad($i, 2, '0', STR_PAD_LEFT);

            $monthMultiOptions[$index] = date('F', mktime(null, null, null, $i, 01));

        }



        $startYear = '1912';

        if (isset($attribs['startYear'])) {

            $startYear = $attribs['startYear'];

            unset($attribs['startYear']);

        }



        $stopYear = $startYear + 100;

        if (isset($attribs['stopYear'])) {

            $stopYear = $attribs['stopYear'];

            unset($attribs['stopYear']);

        }



        $yearMultiOptions = array('' => '');



        if ($stopYear < $startYear) {

            for ($i = $startYear; $i >= $stopYear; $i--) {

                $yearMultiOptions[$i] = $i;

            }

        } else {

            for ($i = $startYear; $i <= $stopYear; $i++) {

                $yearMultiOptions[$i] = $i;

            }

        }





        // return the 3 selects separated by &nbsp;

        return

            $this->view->formSelect(

                $name . '[day]',

                $day,

                $dayAttribs,

                $dayMultiOptions) . '&nbsp;' .

            $this->view->formSelect(

                $name . '[month]',

                $month,

                $monthAttribs,

                $monthMultiOptions) . '&nbsp;' .

            $this->view->formSelect(

                $name . '[year]',

                $year,

                $yearAttribs,

                $yearMultiOptions

            );

    }

}

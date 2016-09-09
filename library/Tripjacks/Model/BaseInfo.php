<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Tripjacks_Model_Info', 'doctrine');

/**
 * Tripjacks_Model_BaseInfo
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $infoid
 * @property string $title
 * @property string $info
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Tripjacks_Model_BaseInfo extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('info');
        $this->hasColumn('infoid', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('title', 'string', 15, array(
             'type' => 'string',
             'length' => 15,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('info', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}
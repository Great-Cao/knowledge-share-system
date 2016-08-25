<?php

use Phpmig\Migration\Migration;

class KsTag extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $table = new Doctrine\DBAL\Schema\Table('tag');
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement'=> true));
        $table->addColumn('text', 'string', array('length' => 20, 'null' => false, 'comment' => '标签内容'));
        $table->addColumn('createdTime', 'integer', array('null' => false, 'comment' => '创建日期'));
        $table->setPrimaryKey(array('id'));

        $container['db']->getSchemaManager()->createTable($table);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();
        $container['db']->getSchemaManager()->dropTable('tag');
    }
}

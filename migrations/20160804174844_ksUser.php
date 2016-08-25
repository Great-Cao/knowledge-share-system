<?php

use Phpmig\Migration\Migration;

class KsUser extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $table = new Doctrine\DBAL\Schema\Table('user');
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement'=> true));
        $table->addColumn('username', 'string', array('length' => 64, 'null' => false, 'comment' => '用户名'));
        $table->addColumn('password', 'string', array('length' => 64, 'null' => false, 'comment' => '密码'));
        $table->addColumn('salt', 'string', array('length' => 64, 'null' => false, 'comment' => '密码加密Salt'));
        $table->addColumn('roles', 'string', array('length' => 512, 'null' => false, 'comment' => '角色'));
        $table->addColumn('updated', 'integer', array('default' => 0, 'signed' => true));
        $table->addColumn('created', 'integer', array('default' => 0, 'signed' => true));
        $table->setPrimaryKey(array('id'));
        $table->addUniqueIndex(array('username'));
        $table->addColumn('followNum', 'integer', array('default' => 0, 'unsigned' => true, 'comment' => '被关注总数'));
        $table->addColumn('score', 'integer', array('default' => 0, 'unsigned' => true, 'comment' => '积分数'));
        $table->addColumn('knowledgeNum', 'integer', array('default' => 0, 'unsigned' => true, 'comment' => '知识分享数'));
        $table->addColumn('browseNum', 'integer', array('default' => 0, 'unsigned' => true, 'comment' => '学习数量'));
        $table->addColumn('imageUrl', 'string', array('default' => 0, 'unsigned' => true, 'comment' => '图片路径'));  
        $container['db']->getSchemaManager()->createTable($table);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();
        $container['db']->getSchemaManager()->dropTable('user');
    }
}

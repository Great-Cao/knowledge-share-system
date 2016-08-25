<?php 

namespace Biz\ToDoList\Dao\Impl;

use Biz\ToDoList\Dao\ToDoListDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class ToDoListDaoImpl extends GeneralDaoImpl implements ToDoListDao
{
    protected $table = 'todolist';

    public function findByUserId($userId)
    {
        return $this->findInField('userId', $userId);
    }

    public function getToDoListByFields($fields)
    {
        return $this->getByFields($fields);
    }

    public function declares()
    {
        return array(
            'timestamps' => array('createdTime'),
            'serializes' => array(),
            'conditions' => array(
                'userId = :userId'
            ),
        );
    }
}

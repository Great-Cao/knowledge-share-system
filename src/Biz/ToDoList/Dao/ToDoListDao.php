<?php

namespace Biz\ToDoList\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface ToDoListDao extends GeneralDaoInterface
{
    public function findByUserId($userId);

    public function getToDoListByFields($fields);
}
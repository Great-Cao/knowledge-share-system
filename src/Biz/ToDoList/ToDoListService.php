<?php

namespace Biz\ToDoList;

interface ToDoListService
{
    public function findToDoListByUserId($userId);

    public function createToDoListKnowledge($id, $userId);

    public function getToDolistCount($conditons);
}
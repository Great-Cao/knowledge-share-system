<?php

namespace Biz\ToDoList\Impl;

use Biz\ToDoList\ToDoListService;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;

class ToDoListServiceImpl extends KernelAwareBaseService implements ToDoListService
{
    public function findToDoListByUserId($userId)
    {
        return $this->getToDoListDao()->findByUserId(array($userId));
    }

    public function getToDolistCount($conditons)
    {
        return $this->getToDoListDao()->count($conditons);
    }

    public function createToDoListKnowledge($id, $userId)
    {
        $knowledge = $this->getKnowledgeDao()->get($id);
        if (empty($knowledge)) {
            throw new \Exception('知识不存在');
        }

        $toreadKnowledge = $this->getToDoListDao()->getToDoListByFields(array(
                'userId' => $userId,
                'knowledgeId' => $id,
            ));
        if (($toreadKnowledge)) {
            throw new \Exception('待读列表中已经有该知识');
        }

        $browsedKnowledge = $this->getLearnDao()->getByIdAndUserId($id, $userId);
        if (!empty($browsedKnowledge)) {
            throw new \Exception('已经学过的知识就不要加入待读列表啦');
        }

        $this->getToDoListDao()->create(array(
            'userId' => $userId,
            'knowledgeId' => $id,
        ));

        return true;
    }

    protected function getToDoListDao()
    {
        return $this->biz['todolist_dao'];
    }

    protected function getKnowledgeDao()
    {
        return $this->biz['knowledge_dao'];
    }

    protected function getLearnDao()
    {
        return $this->biz['learn_dao'];
    }
}
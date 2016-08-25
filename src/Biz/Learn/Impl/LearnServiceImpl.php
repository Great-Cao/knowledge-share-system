<?php

namespace Biz\Learn\Impl;

use Biz\Learn\LearnService;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;

class LearnServiceImpl extends KernelAwareBaseService implements LearnService
{
    public function getLearnedByIdAndUserId($id, $userId)
    {
        return $this->getLearnDao()->getByIdAndUserId($id, $userId);
    }

    public function finishKnowledgeLearn($id, $userId)
    {
        $currentUser = $this->getCurrentUser();

        $fields = array(
            'userId' => $userId,
            'knowledgeId' => $id
        );

        $isToDoList = $this->getToDoListDao()->getToDoListByFields($fields);

        if (!empty($isToDoList)) {
            $this->getToDoListDao()->delete($isToDoList['id']);
        }

        $this->getLearnDao()->create($fields);
        $knowledge = $this->getKnowledgeDao()->get($id);
        $knowledge['viewNum'] += 1; 

        $user = $this->getUserDao()->get($currentUser['id']);
        $user['browseNum'] = $user['browseNum']+1;

        $this->getUserDao()->update($user['id'],$user);

        return $this->getKnowledgeDao()->update($id, $knowledge);
    }

    public function getLearnCount($conditions)
    {
        return $this->getLearnDao()->count($conditions);
    }

    public function findLearnedKnowledgeIds($userId)
    {
        return $this->getLearnDao()->findLearnedIds($userId);
    }

    protected function getLearnDao()
    {
        return $this->biz['learn_dao'];
    }

    protected function getKnowledgeDao()
    {
        return $this->biz['knowledge_dao'];
    }

    protected function getToDoListDao()
    {
        return $this->biz['todolist_dao'];
    }

    protected function getUserDao()
    {
        return $this->biz['user_dao'];
    }
}
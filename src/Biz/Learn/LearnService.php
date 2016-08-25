<?php

namespace Biz\Learn;

interface LearnService
{
    public function getLearnedByIdAndUserId($id, $userId);

    public function finishKnowledgeLearn($id, $userId);

    public function getLearnCount($conditions);

    public function findLearnedKnowledgeIds($userId);
}
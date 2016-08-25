<?php

namespace Biz\Like;

interface LikeService
{
    public function createLike($fields);

    public function deleteLikeByIdAndUserId($id, $userId);

    public function findLikeByUserId($userId);

    public function findLikesByKnowledgeId($knowledgeId);
}
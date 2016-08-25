<?php

namespace Biz\Follow\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface FollowDao extends GeneralDaoInterface
{
    public function getFollowUserByUserIdAndObjectUserId($userId,$objectId);

    public function updateFollowByObjectId($objectId, $addNumber, $type);

    public function clearFollowNewKnowledgeNumByObjectId($type, $objectId);
}
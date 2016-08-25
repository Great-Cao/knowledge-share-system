<?php

namespace Biz\Follow;

interface FollowService
{
    public function findFollowsByUserId($userId);

    public function followUser($userId, $id);

    public function unfollowUser($userId, $id);

    public function followTopic($userId, $topicId);

    public function unFollowTopic($userId, $topicId);

    public function waveFollowNum($ids, $diffs);

    public function findFollowTopicsByUserId($userId);

    public function hasFollowTopics($topics,$userId);

    public function getFollowUserByUserIdAndObjectUserId($userId,$objectId);

    public function getFollowTopicByUserIdAndTopicId($userId, $topicId);

    public function searchMyFollowsByUserIdAndType($userId, $type);

    public function clearFollowNewKnowledgeNumByObjectId($type, $objectId);
}
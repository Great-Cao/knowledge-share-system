<?php

namespace Biz\Topic;

interface TopicService
{
    public function findAllTopics();

    public function getTopicByKnowledgeId($id);

    public function searchTopics($conditions, $orderBy, $start, $limit);

    public function searchTopicsByIds($objectIds, $start, $limit);

    public function findTopTopics($type);

    public function getTopicsCount($conditions);

    public function getTopicById($id, $user);

    public function createTopic($field);

    public function deleteTopicById($id);

    public function findTopicsByIds($ids);
}
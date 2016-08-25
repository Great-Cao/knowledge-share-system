<?php

namespace Biz\Knowledge;

interface KnowledgeService
{
    public function findKnowledges();

    public function getKnowledgesCount($conditions);

    public function createKnowledge($field);

    public function findKnowledgesByUserId($id);

    public function findKnowledgesByKnowledgeIds($knowledgeIds);

    public function getKnowledge($id);

    public function createComment($conditions);

    public function getCommentsCount($conditions);

    public function searchComments($conditions, $orderBy, $start, $limit);

    public function searchKnowledges($conditions, $orderBy, $start, $limit);

    public function updateKnowledge($id, $fields);

    public function deleteKnowledge($id);

    public function searchKnowledgesByIds($ids, $start, $limit);

    public function findTopKnowledges($type);

    public function getTagIds($tags);

    public function getFollowKnowledgesCount($conditions);

    public function searchFollowKnowledges($conditions, $start, $limit);

    public function searchKnowledgesByIdsWithNoOrder($ids, $start, $limit);

    public function findAllTags($conditions,$orderBy,$start,$limit);

    public function moveToPath($file,$user,$knowledge);

    public function updateFollow($filed);

    public function setLearnedMark($knowledges, $userId);
}
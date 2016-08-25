<?php

namespace Biz\Knowledge\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface KnowledgeDao extends GeneralDaoInterface
{
    public function find();

    public function findKnowledgesByUserId($id);

    public function findKnowledgesByKnowledgeIds($knowledgeIds);

    public function searchKnowledgesByIds($ids, $start, $limit);

    public function findByTopicId($id);

    public function getFollowKnowledgesCount($conditions);

    public function searchFollowKnowledges($conditions, $start, $limit);

    public function searchKnowledgesByIdsWithNoOrder($ids, $start, $limit);
}
<?php

namespace Biz\Tag\Impl;

use Biz\Tag\TagService;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;

class TagServiceImpl extends KernelAwareBaseService implements TagService
{
    public function searchTags($conditions, $orderBy, $start, $limit)
    {
        return $this->getTagDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function findTagsByKnowledgeId($id)
    {   
        return $this->getTagKnowledgeDao()->findTagsByKnowledgeId($id);
    }

    public function findTagsByIds($ids)
    {
        return $this->getTagDao()->findTagsByIds($ids);
    }

    protected function getTagDao()
    {
        return $this->biz['tag_dao'];
    }

    protected function getTagKnowledgeDao()
    {
        return $this->biz['tag_knowledge_dao'];
    }
}
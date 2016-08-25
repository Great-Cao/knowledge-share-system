<?php

namespace Biz\Tag;

interface TagService
{
    public function searchTags($conditions, $orderBy, $start, $limit);

    public function findTagsByKnowledgeId($id);

    public function findTagsByIds($ids);
}
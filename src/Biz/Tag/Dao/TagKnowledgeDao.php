<?php

namespace Biz\Tag\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface TagKnowledgeDao extends GeneralDaoInterface
{
    public function findTagsByKnowledgeId($id);
}



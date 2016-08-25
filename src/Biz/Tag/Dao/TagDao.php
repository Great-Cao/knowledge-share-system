<?php

namespace Biz\Tag\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface TagDao extends GeneralDaoInterface
{
    public function findTagsByIds($ids);
}



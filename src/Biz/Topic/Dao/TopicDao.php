<?php

namespace Biz\Topic\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface TopicDao extends GeneralDaoInterface
{
    public function find();
}
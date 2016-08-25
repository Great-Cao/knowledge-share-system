<?php

namespace Biz\Learn\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface LearnDao extends GeneralDaoInterface
{
    public function getByIdAndUserId($id, $userId);

    public function findLearnedIds($userId);
}
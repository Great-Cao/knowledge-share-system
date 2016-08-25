<?php

namespace Biz\Like\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface LikeDao extends GeneralDaoInterface
{
    public function deleteByIdAndUserId($id, $userId);

    public function findByUserId($userId);
}
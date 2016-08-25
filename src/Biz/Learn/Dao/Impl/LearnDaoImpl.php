<?php

namespace Biz\Learn\Dao\Impl;

use Biz\Learn\Dao\LearnDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class LearnDaoImpl extends GeneralDaoImpl implements LearnDao
{
    protected $table = 'user_browse';

    public function getByIdAndUserId($id, $userId)
    {
        $fields = array(
            'userId' => $userId,
            'knowledgeId' => $id
        );
        return $this->getByFields($fields);
    }

    public function findLearnedIds($userId)
    {
        $sql = "SELECT knowledgeId FROM {$this->table} WHERE userId = ?";
        
        return $this->db()->fetchAll($sql, array($userId));
    }

    public function declares()
    {
        return array(
            'timestamps' => array('createdTime'),
            'serializes' => array(),
            'conditions' => array(
                'userId = :userId',
            ),
        );
    }
}
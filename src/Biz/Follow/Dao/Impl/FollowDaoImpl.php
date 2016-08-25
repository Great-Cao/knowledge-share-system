<?php 

namespace Biz\Follow\Dao\Impl;

use Biz\Follow\Dao\FollowDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class FollowDaoImpl extends GeneralDaoImpl implements FollowDao
{
    protected $table = "follow";

    public function getFollowUserByUserIdAndObjectUserId($userId, $objectId)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE userId = ? AND objectId = ? AND type = ?";

        return $this->db()->fetchAssoc($sql, array($userId, $objectId,'user')) ?: null;
    }

    public function updateFollowByObjectId($objectId, $addNumber, $type)
    {
        $sql = "UPDATE {$this->table()} SET newKnowledgeNum = newKnowledgeNum + ? where type = ? AND objectId = ?";

        return $this->db()->executeUpdate($sql, array($addNumber, $type ,$objectId));
    }

    public function clearFollowNewKnowledgeNumByObjectId($type, $objectId)
    {
        $sql = "UPDATE {$this->table()} SET newKnowledgeNum = 0 where type = ? AND objectId = ?";

        return $this->db()->executeUpdate($sql, array($type, $objectId));
    }

    public function declares()
    {
        return array(
            'timestamps' => array(),
            'serializes' => array(),
            'conditions' => array(
                'userId = :userId', 
                'objectId = :objectId',
                'type = :type',
            ),
        );
    }
}
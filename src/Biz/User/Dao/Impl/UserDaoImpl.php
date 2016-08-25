<?php

namespace Biz\User\Dao\Impl;

use Biz\User\Dao\UserDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class UserDaoImpl extends GeneralDaoImpl implements UserDao
{
    protected $table = 'user';

    public function get($id)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE id = ?";

        return $this->db()->fetchAssoc($sql, array($id)) ?: null;
    }
    
    public function findUsersByIds($ids)
    {
        if (empty($ids)) {
            return array();
        }

        $marks = str_repeat('?,', count($ids)-1).'?';

        $sql = "SELECT * FROM {$this->table} WHERE id IN ({$marks})";

        return $this->db()->fetchAll($sql,$ids);
    }

    public function searchUsers($objectIds, $start, $limit)
    {
        if (empty($objectIds)) {
            return array();
        }

        $marks = str_repeat('?,', count($objectIds)-1).'?';
        $start = (int) $start;
        $limit = (int) $limit;

        $sql = "SELECT * FROM {$this->table} WHERE id IN ({$marks}) LIMIT {$start}, {$limit}";

        return $this->db()->fetchAll($sql,$objectIds);
    }

    public function getByUsername($username)
    {
        return $this->getByFields(array('username' => $username));
    }

    public function declares()
    {
        return array(
            'timestamps' => array('created', 'updated'),
            'serializes' => array('roles' => 'delimiter'),
            'conditions' => array(
                'username like :username'
            ),
        );
    }
}

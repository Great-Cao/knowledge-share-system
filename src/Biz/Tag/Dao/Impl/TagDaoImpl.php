<?php

namespace Biz\Tag\Dao\Impl;

use Biz\Tag\Dao\TagDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class TagDaoImpl extends GeneralDaoImpl implements TagDao
{
    protected $table = 'tag';

    public function findTagsByIds($tagIds)
    {
        if (empty($tagIds)) {
            return array();
        }

        $marks = str_repeat('?,', count($tagIds)-1).'?';
        $sql = "SELECT * FROM {$this->table} WHERE id IN ({$marks})";

        return $this->db()->fetchAll($sql,$tagIds);
    }

    public function declares()
    {
        return array(
            'timestamps' => array('createdTime'),
            'serializes' => array(),
            'conditions' => array(
                'name = :name',
                'id IN (:ids)',
                'text = :text'
            ),
        );        
    }
}
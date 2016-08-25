<?php

namespace Biz\Tag\Dao\Impl;

use Biz\Tag\Dao\TagKnowledgeDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class TagKnowledgeDaoImpl extends GeneralDaoImpl implements TagKnowledgeDao
{
    protected $table = 'knowledge_tag';

    public function findTagsByKnowledgeId($id)
    {   
        $sql = "SELECT * FROM {$this->table()} WHERE knowledgeId = ?";

        return $this->db()->fetchAll($sql, array($id)) ?: null;
    }

    
    public function declares()
    {
        return array(
            'timestamps' => array('createdTime'),
            'serializes' => array(),
            'conditions' => array(
                'knowledgeId = :knowledgeId',
                'tagId       = :tagId' 
            ),
        );        
    }
}
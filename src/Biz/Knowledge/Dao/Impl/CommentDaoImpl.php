<?php

namespace Biz\Knowledge\Dao\Impl;

use Biz\Knowledge\Dao\CommentDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class CommentDaoImpl extends GeneralDaoImpl implements CommentDao
{
    protected $table = 'comment';

    public function declares()
    {
        return array(
            'timestamps' => array('createdTime'),
            'serializes' => array(),
            'conditions' => array(
                'userId = :userId',
                'knowledgeId = :knowledgeId',
                'value = :value'
            ),
        );
    }
}
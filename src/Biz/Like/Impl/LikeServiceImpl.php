<?php

namespace Biz\Like\Impl;

use Biz\Like\LikeService;
use AppBundle\Common\ArrayToolKit;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;

class LikeServiceImpl extends KernelAwareBaseService implements LikeService
{
    public function createLike($fields)
    {
        return $this->getLikeDao()->create($fields);
    }

    public function deleteLikeByIdAndUserId($id, $userId)
    {
        return $this->getLikeDao()->deleteByIdAndUserId($id, $userId);
    }

    public function findLikeByUserId($userId)
    {
        return $this->getLikeDao()->findByUserId($userId);
    }

    public function findLikesByKnowledgeId($knowledgeId)
    {
        return $this->getLikeDao()->search(
            array('knowledgeId' => $knowledgeId),
            array('createdTime', 'DESC'),
            0,
            15
        );
    }

    public function haslikedKnowledge($knowledge,$userId)
    {

        $likes = $this->findLikeByUserId($userId);
        $likeKnowledgeIds = ArrayToolKit::column($likes, 'knowledgeId');

        $hasliked = array();
        foreach ($knowledge as $singleKnowledge) {
            if (empty($likeKnowledgeIds)) {
                $singleKnowledge['isLiked'] = false;
            } else {
                if(in_array($singleKnowledge['id'], $likeKnowledgeIds)) {
                    $singleKnowledge['isLiked'] = true;
                } else {
                    $singleKnowledge['isLiked'] = false;
                }
            }
            $hasliked[] = $singleKnowledge;
        }
        return $hasliked;
    }        

    public function dislikeKnowledge($id, $userId)
    {
        $this->getLikeDao()->deleteByIdAndUserId($id, $userId);
        $knowledge = $this->getKnowledgeDao()->get($id);
        $knowledge['likeNum'] = $knowledge['likeNum'] - 1; 
        return $this->getKnowledgeDao()->update($id, $knowledge);
    }

    public function likeKnowledge($id, $userId)
    {
        $fields = array(
            'userId' => $userId,
            'knowledgeId' => $id
        );

        $this->getLikeDao()->create($fields);
        $knowledge = $this->getKnowledgeDao()->get($id);
        $knowledge['likeNum'] += 1; 
        return $this->getKnowledgeDao()->update($id, $knowledge);
    }

    protected function getLikeDao()
    {
        return $this->biz['like_dao'];
    }

    protected function getKnowledgeDao()
    {
        return $this->biz['knowledge_dao'];
    }
}
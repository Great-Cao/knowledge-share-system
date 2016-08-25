<?php 

namespace Biz\Follow\Impl;

use Biz\Follow\FollowService;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;

class FollowServiceImpl extends KernelAwareBaseService implements FollowService
{
    public function followUser($userId, $id)
    {        
        $objectUser = $this->getUserDao()->get($id);

        if (empty($objectUser)) {
            throw new \Exception('被关注的用户不存在');
        }

        $objectUser['followNum'] += 1;
        $this->getUserDao()->update($objectUser['id'],$objectUser);

        $followUser = $this->getFollowDao()->create(array(
            'userId'=> $userId,
            'type'=>'user',
            'objectId'=>$id
        ));

        if ($userId == $followUser['userId'] && $followUser['objectId'] == $id) {
            return true;
        } else {
            throw new \RuntimeException("关注该用户失败");
        }    
    }

    public function unfollowUser($userId, $id)
    {   
        $objectUser = $this->getUserDao()->get($id);

        if (empty($objectUser)) {
            throw new \Exception('被关注的用户不存在');
        }

        $objectUser['followNum'] = $objectUser['followNum'] - 1;
        $this->getUserDao()->update($objectUser['id'],$objectUser);
        
        $followUser = $this->getFollowDao()->getFollowUserByUserIdAndObjectUserId($userId, $id);
        
        $status = $this->getFollowDao()->delete($followUser['id']);
        if ($status == 1) {
            return true;
        } else {
            throw new \RuntimeException("取消关注该用户失败");
        }  
    }

    public function followTopic($userId, $topicId)
    {
        if (empty($userId)) {
            throw new \Exception('用户不存在');
        }
        $topic = $this->getTopicDao()->get($topicId);
        if (empty($topic)) {
            throw new \Exception('主题不存在');
        }

        $followed = $this->getFollowTopicByUserIdAndTopicId($userId, $topicId);
        if ($followed) {
            throw new \Exception('已经被关注');
        }

        $this->getFollowDao()->create(array(
            'objectId' => $topicId,
            'userId' => $userId,
            'type' => 'topic',
        ));

        $ids = array($topicId);
        $diffs = array('followNum' => 1);
        $this->waveFollowNum($ids, $diffs);

        return true;
    }

    public function unFollowTopic($userId, $topicId)
    {
        if (empty($userId)) {
            throw new \Exception('用户不存在');
        }

        $topic = $this->getTopicDao()->get($topicId);
        if (empty($topic)) {
            throw new \Exception('主题不存在');
        }

        $followed = $this->getFollowTopicByUserIdAndTopicId($userId, $topicId);
        if (empty($followed)) {
            throw new \Exception('未被关注');
        }
        
        $this->getFollowDao()->delete($followed[0]['id']);

        $ids = array($topicId);
        $diffs = array('followNum' => -1);
        $this->waveFollowNum($ids, $diffs);


        return true;
    }

    public function getFollowTopicByUserIdAndTopicId($userId, $topicId)
    {
        $conditions = array(
            'userId' => $userId,
            'objectId' => $topicId,
            'type' => 'topic',
        );

        $orderBy = array('objectId', 'ASC');

        return $this->getFollowDao()->search($conditions, $orderBy, 0, PHP_INT_MAX);
    }

    public function getFollowUserByUserIdAndObjectUserId($userId,$objectId)
    {
        $objectUser = $this->getFollowDao()->getFollowUserByUserIdAndObjectUserId($userId,$objectId);
        if (isset($objectUser)) {
            return true;
        } else {
            return false;
        }
    }

    public function findFollowTopicsByUserId($userId)
    {
        $conditions = array(
            'userId' => $userId,
            'type' => 'topic',
        );
        $orderBy = array('objectId', 'ASC');
        
        return $this->getFollowDao()->search($conditions, $orderBy, 0, PHP_INT_MAX);
    }

    public function findFollowUsersByUserId($userId)
    {
        $conditions = array(
            'userId' => $userId,
            'type' => 'user',
        );
        $orderBy = array('objectId', 'ASC');
        
        return $this->getFollowDao()->search($conditions, $orderBy, 0, PHP_INT_MAX);
    }

    public function waveFollowNum($ids, $diffs)
    {
        return $this->getTopicDao()->wave($ids, $diffs);
    }

    public function hasFollowTopics($topics,$userId)
    {
        $followedTopics = $this->findFollowTopicsByUserId($userId);
        $followedTopicIds = array();
        foreach ($followedTopics as $value) {
            $followedTopicIds[] = $value['objectId'];
        }
        foreach ($topics as $key => $topic) {
            $topics[$key]['hasFollow'] = false;
            if (in_array($topic['id'], $followedTopicIds)) {
                $topics[$key]['hasFollow'] = true;
            }
        }
        return $topics;
    }

    public function hasFollowUsers($users,$userId)
    {
        $followedUsers = $this->findFollowUsersByUserId($userId);
        $followedUserIds = array();
        foreach ($followedUsers as $value) {
            $followedUserIds[] = $value['objectId'];
        }
        foreach ($users as $key => $user) {
            $users[$key]['hasFollow'] = false;
            if (in_array($user['id'], $followedUserIds)) {
                $users[$key]['hasFollow'] = true;
            }
        }
        return $users;
    }

    public function searchMyFollowsByUserIdAndType($userId, $type)
    {
        $conditions = array(
            'userId' => $userId, 
            'type' => $type
        );
        $orderBy = array('id', 'DESC');
        
        $myFollows = $this->getFollowDao()->search($conditions, $orderBy, 0, PHP_INT_MAX);

        return $myFollows;
    }

    public function findFollowsByUserId($userId)
    {
        return $this->getFollowDao()->search(
            array('userId' => $userId),
            array('id', 'DESC'),
            0,
            PHP_INT_MAX
        );
    }

    public function clearFollowNewKnowledgeNumByObjectId($type, $objectId)
    {
       return $this->getFollowDao()->clearFollowNewKnowledgeNumByObjectId($type, $objectId);
    }

    protected function getFollowDao()
    {
        return $this->biz['follow_dao'];
    }

    protected function getTopicDao()
    {
        return $this->biz['topic_dao'];
    }

    protected function getUserDao()
    {
        return $this->biz['user_dao'];
    }
}
<?php
namespace Biz\Topic\Impl;

use Biz\Topic\Dao\Impl\TopicDaoImpl;
use Biz\Topic\TopicService;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;

class TopicServiceImpl extends KernelAwareBaseService implements TopicService
{
    public function createTopic($field)
    {
        if (empty($field)) {
            throw new \Exception('添加内容不能为空');
        }
        $topic = $this->getTopicDao()->get($field['name']);
        if (!empty($topic)) {
            return $topic;
        }

        return $this->getTopicDao()->create($field);
    }

    public function getTopicById($id,$user)
    {
        if ($id == '请输入搜索主题') {
            return array('id' => 0);
        }
        $field = array('name' => $id,'userId' => $user['id']);
        if (is_numeric($id)) {
            $result = $this->getTopicDao()->get($id);
            return $this->getTopicDao()->get($id) ? : $this->getTopicDao()->create($field);
        } else {
            return $this->getTopicDao()->create($field);
        }
    }

    public function getTopicByKnowledgeId($id)
    {
        return $this->getTopicDao()->get($id);
    }

    public function findTopTopics($type)
    {
        $topConditions = array();
        $topOrderBy = array($type.'Num', 'DESC');
        $topNum = 5;
        $topTopics = $this->getTopicDao()->search(
            $topConditions,
            $topOrderBy,
            0,
            $topNum
        );

        return $topTopics;
    }

    public function deleteTopicById($id)
    {
        $topic = $this->getTopicDao()->get($id);
        if (empty($topic)) {
            throw new \Exception('主题不存在,删除失败!');
        }
        return $this->getTopicDao()->delete($id);
    }

    public function findAllTopics()
    {
        $topics = $this->getTopicDao()->find();

        return $topics;
    }

    public function searchTopics($conditions, $orderBy, $start, $limit)
    {
        $topics = $this->getTopicDao()->search($conditions, $orderBy, $start, $limit);

        return $topics;
    }

    public function searchTopicsByIds($objectIds, $start, $limit)
    {
        $topics = $this->getTopicDao()->searchTopicsByIds($objectIds, $start, $limit);

        return $topics;
    }

    public function findTopicsByIds($ids)
    {
        return $this->getTopicDao()->findTopicsByIds($ids);
    }

    public function getTopicsCount($conditions)
    {
        return $this->getTopicDao()->count($conditions);
    }

    protected function getTopicDao()
    {
        return $this->biz['topic_dao'];
    }
}
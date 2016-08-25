<?php

namespace Biz\Knowledge\Impl;

use Biz\Knowledge\KnowledgeService;
use AppBundle\Common\ArrayToolKit;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;
use AppBundle\Common\UpLoad;
use Symfony\Component\Filesystem\Filesystem;

class KnowledgeServiceImpl extends KernelAwareBaseService implements KnowledgeService
{
    public function updateKnowledge($id, $fields)
    {
        $fields = ArrayToolkit::filter($fields, array(
            'title'   => '',
            'summary' => '',
            'content' => ''
        ));

        if (empty($fields['title'])) {
            throw new \RuntimeException('标题不能为空！');
        }

        if (empty($fields['summary'])) {
            throw new \RuntimeException('摘要不能为空！');
        }

        return $this->getKnowledgeDao()->update($id, $fields);
    }

    public function searchFollowKnowledges($conditions, $start, $limit)
    {
        return $this->getKnowledgeDao()->searchFollowKnowledges($conditions, $start, $limit);
    }

    public function getFollowKnowledgesCount($conditions)
    {
        return $this->getKnowledgeDao()->getFollowKnowledgesCount($conditions);
    }

    public function findTopKnowledges($type)
    {
        $topConditions = array();
        $topOrderBy = array($type.'Num', 'DESC');
        $topNum = 5;
        $topKnowledges = $this->getKnowledgeDao()->search(
            $topConditions,
            $topOrderBy,
            0,
            $topNum
        );

        return $topKnowledges;
    }

    public function getTagIds($tags)
    {
        if (empty($tags[0])) {
            return array('id' => 0);
        }
        $allTags = $this->findAllTags(array(),array('createdTime','DESC'),0,PHP_INT_MAX);
        $allTagIds = ArrayToolKit::column($allTags,'id');
        $result = array();
        foreach ($tags as $key => $tag) {
            if (in_array($tag, $allTagIds)) {
                $result[] = $tag;
            } else {
                $result[] = $this->getTagDao()->create(array('text' => $tag))['id'];
            }
        }

        return $result;
    }

    public function findAllTags($conditions,$orderBy,$start,$limit)
    {
        return $this->getTagDao()->search($conditions,$orderBy,$start,$limit);
    }

    public function moveToPath($file,$user,$knowledge)
    {
        if (empty($file)) {
            throw new \Exception("上传文档不能为空!");
        } elseif (abs(filesize($file)) > 20971520) {
            throw new \Exception("文件不能大于20M!");
        } elseif (empty($knowledge['title'])) {
            throw new \Exception("标题不能为空!");
        } elseif (strlen($knowledge['title']) > 60) {
            throw new \Exception("标题不能超过20个汉字!");
        } elseif (strlen($knowledge['topic']) > 60) {
            throw new \Exception("主题名不能超过20个汉字!");
        }
        
        $upLoad = new UpLoad($file);
        $fileName = date('Y-m-d H:i:s',time()).'-'.$knowledge['title'];
        $path = __DIR__.'/../../../../web/files/'.$user['username'];
        $path = $upLoad->moveToPath($path,$fileName);

        return $fileName;
    }

    public function moveImageToPath($file,$user)
    {   
        $upLoad = new UpLoad($file);
        $fileSystem = new Filesystem();
        $path = $_SERVER['DOCUMENT_ROOT'].'/picture/';
        // $extension = $file->getClientOriginalExtension();
        $fileName = $user['username'];
        if ($fileSystem->exists($path.$fileName)) {
            $fileSystem->remove($path.$fileName);
        }
        $upLoad->moveToPath($path, $fileName);
        $path = 'picture/'.$fileName;
        $this->getUserDao()->update($user['id'],array(
            'imageUrl' => $path
        ));
        unlink(dirname($_SERVER['DOCUMENT_ROOT']).'/app/cache');
    }

    public function deleteKnowledge($id)
    {
        return $this->getKnowledgeDao()->delete($id);
    }

    public function getKnowledgesCount($conditions)
    {
        return $this->getKnowledgeDao()->count($conditions);
    }

    public function findKnowledges()
    {
        return $this->getKnowledgeDao()->find();
    }

    public function findKnowledgesByUserId($id)
    {
        return $this->getKnowledgeDao()->findKnowledgesByUserId($id);
    }

    public function findKnowledgesByKnowledgeIds($knowledgeIds)
    {
        return $this->getKnowledgeDao()->findKnowledgesByKnowledgeIds($knowledgeIds);
    }
    
    public function createKnowledge($field)
    {
        $currentUser = $this->getCurrentUser();

        $this->updateFollow($field);
        $tagId = $field['tagId'];
        $string = implode('|', $tagId);
        $field['tagId'] = $string;

        $user = $this->getUserDao()->get($currentUser['id']);
        $user['knowledgeNum'] += 1;
        $this->getUserDao()->update($user['id'],$user);

        return $this->getKnowledgeDao()->create($field);
    }
    
    public function getKnowledge($id)
    {
        return $this->getKnowledgeDao()->get($id);
    }

    public function updateFollow($filed)
    {
        $currentUser = $this->getCurrentUser();
        $topicId = $filed['topicId'];
        $userId = $currentUser['id'];
        $addNumber = 1;
        $this->getFollowDao()->updateFollowByObjectId($topicId, $addNumber, $type = 'topic');
        $this->getFollowDao()->updateFollowByObjectId($userId, $addNumber, $type = 'user');
        return true;
    }

    public function createComment($conditions)
    {
        if (empty($conditions['value'])) {
            throw new \RuntimeException("评论内容为空！");
        } elseif (strlen($conditions['value']) > 100) {
            throw new \RuntimeException("评论内容不能超过100字！");
        }

        return $this->getCommentDao()->create($conditions);
    }

    public function getCommentsCount($conditions)
    {
        return $this->getCommentDao()->count($conditions);
    }

    public function searchComments($conditions, $orderBy, $start, $limit)
    {
        return $this->getCommentDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function searchKnowledges($conditions, $orderBy, $start, $limit)
    {
        return $this->getKnowledgeDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function setToreadMark($knowledges, $userId)
    {
        $toreadKnowledge =  $this->getToDoListDao()->findByUserId(array($userId));
        $toreadKnowledgeIds = ArrayToolkit::index($toreadKnowledge, 'knowledgeId');
        foreach ($knowledges as $key => $value) {
            if (isset($toreadKnowledgeIds[$value['id']])) {
                $knowledges[$key]['toread'] = true;
            }
        }

        return $knowledges;
    }

    public function setLearnedMark($knowledges, $userId)
    {
        $learnedIds = $this->getLearnDao()->findLearnedIds($userId);
        $learnedIds = ArrayToolkit::index($learnedIds, 'knowledgeId');
        foreach ($knowledges as $key => $value) {
            if (isset($learnedIds[$value['id']])) {
                $knowledges[$key]['learned'] = true;
            }
        }

        return $knowledges;
    }

    public function searchKnowledgesByIds($ids, $start, $limit)
    {
        return $this->getKnowledgeDao()->searchKnowledgesByIds($ids, $start, $limit);
    }

    public function searchKnowledgesByIdsWithNoOrder($ids, $start, $limit)
    {
        return $this->getKnowledgeDao()->searchKnowledgesByIdsWithNoOrder($ids, $start, $limit);
    }

    public function isCorrectLink($knowledge)
    {
        if ($knowledge['content'] == 'file') {
            return $knowledge;
        }

        if (!preg_match("/^((http|ftp|https):\/\/)?/", $knowledge['content'])) {
            $knowledge['content'] = 'http://'.$knowledge['content'];
        }

        return $knowledge;
    }

    protected function getKnowledgeDao()
    {
        return $this->biz['knowledge_dao'];
    }

    protected function getTagDao()
    {
        return $this->biz['tag_dao'];
    }

    protected function getCommentDao()
    {
        return $this->biz['comment_dao'];
    }

    protected function getToDoListDao()
    {
        return $this->biz['todolist_dao'];
    }

    public function getFollowDao()
    {
        return $this->biz['follow_dao'];
    }
    protected function getLearnDao()
    {
        return $this->biz['learn_dao'];
    }

    protected function getUserDao()
    {
        return $this->biz['user_dao'];
    }
}
<?php

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

class KnowledgeServiceTest extends BaseTestCase
{
    public function testGetKnowledgesCount()
    {
        $knowledge[0] = array(
            'title' => '测试１',
            'summary' => '测试',
            'type' => 'file',
            'topicId' => 1,
            'userId' => 1,
            'createdTime' => 2016810,
            'updatedTime' => 2016811,
            'content' => '这是测试',
            'favoriteNum' => 10,
            'likeNum' => 10
        );
        $knowledge[1] = array(
            'title' => '测试2',
            'summary' => '测试',
            'type' => 'link',
            'topicId' => 1,
            'userId' => 1,
            'createdTime' => 2016810,
            'updatedTime' => 2016811,
            'content' => '这是测试',
            'favoriteNum' => 10,
            'likeNum' => 10  
        );
        $condition = array('userId' => 1);
        $this->getKnowledgeService()->createKnowledge($knowledge[0]);
        $this->getKnowledgeService()->createKnowledge($knowledge[1]);
        $count = $this->getKnowledgeService()->getKnowledgesCount($condition);
        $this->assertEquals(2, $count);
    }

    public function testUpdateKnowledge()
    {
        $knowledge = array(
            'title' => '测试１',
            'summary' => '测试1',
            'type' => 'file',
            'topicId' => 1,
            'userId' => 1,
            'createdTime' => 2016810,
            'updatedTime' => 2016811,
            'content' => '这是测试1',
            'favoriteNum' => 10,
            'likeNum' => 10
        );
        $knowledged = $this->getKnowledgeService()->createKnowledge($knowledge);
        $updateKnowledge = array(
            'title' => '测试2',
            'summary' => '测试2',
            'type' => 'file',
            'topicId' => 1,
            'userId' => 1,
            'createdTime' => 2016810,
            'updatedTime' => 2016811,
            'content' => '这是测试2',
            'favoriteNum' => 10,
            'likeNum' => 10
        );
        $updatedKnowledge = $this->getKnowledgeService()->updateKnowledge($knowledged['id'],$updateKnowledge);
        $this->assertEquals($updateKnowledge['title'],$updatedKnowledge['title']);
        $this->assertEquals($updateKnowledge['summary'],$updatedKnowledge['summary']);
        $this->assertEquals($updateKnowledge['type'],$updatedKnowledge['type']);
        $this->assertEquals($updateKnowledge['content'],$updatedKnowledge['content']);
    }

    public function testDeleteKnowledge()
    {
        $knowledge = array(
            'title' => '测试１',
            'summary' => '测试1',
            'type' => 'file',
            'topicId' => 1,
            'userId' => 1,
            'createdTime' => 2016810,
            'updatedTime' => 2016811,
            'content' => '这是测试1',
            'favoriteNum' => 10,
            'likeNum' => 10
        ); 
        $knowledged = $this->getKnowledgeService()->createKnowledge($knowledge);
        $result = $this->getKnowledgeService()->deleteKnowledge($knowledged['id']);
        $knowledge = $this->getKnowledgeService()->getKnowledge($knowledged['id']);
        $this->assertEquals(1,$result);
        $result = $this->getKnowledgeService()->deleteKnowledge($knowledged['id']);
        $this->assertEquals(0,$result);
    }

    public function testSearchKnowledges()
    {
        $knowledge0 = array(
            'title' => '测试１',
            'summary' => '测试1',
            'type' => 'file',
            'topicId' => 1,
            'userId' => 1,
            'createdTime' => 2016810,
            'updatedTime' => 2016811,
            'content' => '这是测试1',
            'favoriteNum' => 10,
            'likeNum' => 10
        );
        $knowledge1 = array(
            'title' => '测试１',
            'summary' => '测试1',
            'type' => 'file',
            'topicId' => 1,
            'userId' => 1,
            'createdTime' => 2016810,
            'updatedTime' => 2016811,
            'content' => '这是测试1',
            'favoriteNum' => 10,
            'likeNum' => 10
        );
        $condition = array('userId' => 1);
        $this->getKnowledgeService()->createKnowledge($knowledge0);
        $this->getKnowledgeService()->createKnowledge($knowledge1);  
        $knowledges = $this->getKnowledgeService()->searchKnowledges($condition,array('createdTime','DESC'),0,2);
        $this->assertEquals($knowledge0['title'],$knowledges[0]['title']);
        $this->assertEquals($knowledge0['summary'],$knowledges[0]['summary']);
        $this->assertEquals($knowledge0['content'],$knowledges[0]['content']);
        $this->assertEquals($knowledge1['title'],$knowledges[1]['title']);
        $this->assertEquals($knowledge1['summary'],$knowledges[1]['summary']);
        $this->assertEquals($knowledge1['content'],$knowledges[1]['content']);
    }

    public function testCreateKnowledge()
    {
        $data = array(
            'title' => 'title',
            'summary' => 'summary',
            'content' => 'content',
            'type' => 'file',
            'userId' => 1,
        );
        $knowledge = $this->getKnowledgeService()->createKnowledge($data);
        $result = $this->getKnowledgeService()->getKnowledge($knowledge['id']);

        $this->assertEquals($knowledge['title'], $result['title']);
        $this->assertEquals($knowledge['summary'], $result['summary']);
        $this->assertEquals($knowledge['content'], $result['content']);
        $this->assertEquals($knowledge['type'], $result['type']);
        $this->assertEquals($knowledge['userId'], $result['userId']);
    }

    public function testCreateComment()
    {
        $comment1 = array(
            'value' => '评论测试',
            'userId' => 1,
            'knowledgeId' => 1
        );
        $comment1 = $this->getCommentDao()->create($comment1);
        $result = $this->getCommentDao()->get(1);

        $this->assertEquals($comment1['value'], $result['value']);
    }

    public function testGetCommentsCount()
    {
        $comment1 = array(
            'value' => '评论测试1',
            'userId' => 1,
            'knowledgeId' => 1
        );
        $comment2 = array(
            'value' => '评论测试2',
            'userId' => 1,
            'knowledgeId' => 2  
        );
        $this->getCommentDao()->create($comment1);
        $this->getCommentDao()->create($comment2);
        $condition = array('userId' => 1);
        $count = $this->getKnowledgeService()->getCommentsCount($condition);
        $this->assertEquals(2, $count);
    }

    public function testSearchComments()
    {
        $comment1 = array(
            'value' => '评论测试1',
            'userId' => 1,
            'knowledgeId' => 1
        );
        $comment2 = array(
            'value' => '评论测试2',
            'userId' => 1,
            'knowledgeId' => 2
        );
        $this->getCommentDao()->create($comment1);
        $this->getCommentDao()->create($comment2);
        $condition = array('userId' => 1); 
        $result = $this->getKnowledgeService()->searchComments($condition,array('createdTime','DESC'),0,2);
        $this->assertEquals($comment1['value'],$result[0]['value']);
        $this->assertEquals($comment2['value'],$result[1]['value']);
    }

    public function testSearchKnowledgesByIds()
    {
        $data1 = array(
            'title' => 'title',
            'summary' => 'summary',
            'content' => 'content',
            'type' => 'file',
            'userId' => 1,
        );

        $data2 = array(
            'title' => 'title1',
            'summary' => 'summary1',
            'content' => 'content1',
            'type' => 'file1',
            'userId' => 1,
        );

        $knowledge1 = $this->getKnowledgeService()->createKnowledge($data1);
        $knowledge2 = $this->getKnowledgeService()->createKnowledge($data2);

        $ids = array($knowledge1['id'],$knowledge2['id']);
        $results = $this->getKnowledgeService()->searchKnowledgesByIds($ids, 0, PHP_INT_MAX);

        $this->assertEquals(2, count($results));


    }

    protected function getKnowledgeService()
    {
        return self::$kernel['knowledge_service'];
    }

    protected function getCommentDao()
    {
        return self::$kernel['comment_dao'];
    }
}
<?php

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

class LikeServiceTest extends BaseTestCase
{
    public function testCreateLike()
    {
        $likeFields = array(
            'userId' => 1,
            'knowledgeId'=>1
        );

        $like = $this->getLikeService()->createLike($likeFields);
        $this->assertEquals(1,$like['userId']);
    }

    public function testDeleteLikeByIdAndUserId()
    {
        $likeFields = array(
            'Fields1' => array(
                'userId' => 1,
                'knowledgeId'=>1
            ),
            'Fields2' => array(
                'userId' => 2,
                'knowledgeId'=>2
            )
        );

        $this->getLikeService()->createLike($likeFields['Fields1']);
        $this->getLikeService()->createLike($likeFields['Fields2']);

        $this->getLikeService()->deleteLikeByIdAndUserId('2','2');
        $like2 = $this->getLikeDao()->get('2');

        $this->assertNull($like2);
    }

    public function testFindLikeByUserId()
    {
        $likeFields = array(
            'Fields1' => array(
                'userId' => 1,
                'knowledgeId'=>1
            ),
            'Fields2' => array(
                'userId' => 1,
                'knowledgeId'=>2
            )
        );

        $this->getLikeService()->createLike($likeFields['Fields1']);
        $this->getLikeService()->createLike($likeFields['Fields2']);

        $likes = $this->getLikeService()->findLikeByUserId('1');

        $this->assertEquals(2,count($likes));
    }

    public function testHaslikedKnowledge()
    {
        $userId = '1';
        $knowledge = array(
            'singleKnowledge1' => array(
                'id' => 1,
                'title' => '知识分享',
                'summary' => '这是测试',
                'type' => 'link',
                'topicId' => '1',
                'userId' => '1',
                'createdTime' => '1464591741',
                'updatedTime' => '1470837949',
                'content' => 'www.baidu.com',
                'favoriteNum' => '1',
                'likeNum' => '1'
            ),
            'singleKnowledge2' => array(
                'id' => 2,
                'title' => '知识分享1',
                'summary' => '这是测试1',
                'type' => 'link',
                'topicId' => '1',
                'userId' => '1',
                'createdTime' => '1464591742',
                'updatedTime' => '1470837949',
                'content' => 'www.baidu.com',
                'favoriteNum' => '233',
                'likeNum' => '233'
            )
        );

        $likeFields = array(
            'Fields1' => array(
                'userId' => 1,
                'knowledgeId'=>1
            )
        );

        $this->getLikeService()->createLike($likeFields['Fields1']);
        $hasliked = $this->getLikeService()->haslikedKnowledge($knowledge,$userId);

        $this->assertTrue($hasliked[0]['isLiked']);

    }

    public function testLikeKnowledge()
    {
        $userId = '1';
        $knowledge = array(
            'singleKnowledge1' => array(
                'id' => 1,
                'title' => '知识分享',
                'summary' => '这是测试',
                'type' => 'link',
                'topicId' => '1',
                'userId' => '1',
                'createdTime' => '1464591741',
                'updatedTime' => '1470837949',
                'content' => 'www.baidu.com',
                'favoriteNum' => 0,
                'likeNum' => 0
            )
        );

        $this->getKnowledgeService()->createKnowledge($knowledge['singleKnowledge1']);

        $result = $this->getLikeService()->likeKnowledge('1','1');

        $this->assertEquals(1,$result['likeNum']);
    }

    public function testDislikeKnowledge()
    {
        $userId = '1';
        $knowledge = array(
            'singleKnowledge1' => array(
                'id' => 1,
                'title' => '知识分享',
                'summary' => '这是测试',
                'type' => 'link',
                'topicId' => '1',
                'userId' => '1',
                'createdTime' => '1464591741',
                'updatedTime' => '1470837949',
                'content' => 'www.baidu.com',
                'favoriteNum' => 0,
                'likeNum' => 1
            )
        );

        $this->getKnowledgeService()->createKnowledge($knowledge['singleKnowledge1']);

        $result = $this->getLikeService()->likeKnowledge('1','1');  

        $result = $this->getLikeService()->dislikeKnowledge('1','1');

        $this->assertEquals(1,$result['likeNum']);        
    }

    protected function getLikeService()
    {
        return self::$kernel['like_service'];
    }

    protected function getLikeDao()
    {
        return self::$kernel['like_dao'];
    }

    protected function getKnowledgeService()
    {
        return self::$kernel['knowledge_service'];
    }
}

<?php

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

class LearnServiceTest extends BaseTestCase
{
    public function testGetLearnedByIdAndUserId()
    {
        $learnFields = array(
            'Fields1' => array(
                'userId' => 1,
                'knowledgeId'=>1
            ),
            'Fields2' => array(
                'userId' => 1,
                'knowledgeId'=>2
            )
        );

        $this->getLearnDao()->create($learnFields['Fields1']);
        $this->getLearnDao()->create($learnFields['Fields2']);

        $result = $this->getLearnService()->getLearnedByIdAndUserId('2', '1'); 
        $this->assertEquals(1, $result['userId']);
        $this->assertEquals(2, $result['knowledgeId']);     
    }

    public function testFinishKnowledgeLearn()
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
                'likeNum' => 0,
                'pageView' => 0
            )
        );

        $this->getKnowledgeDao()->create($knowledge['singleKnowledge1']);

        $knowledgeResult = $this->getLearnService()->finishKnowledgeLearn('1','1');
        $learnResult = $this->getLearnService()->getLearnedByIdAndUserId('1','1');
        $this->assertEquals(1,$knowledgeResult['pageView']);
        $this->assertNotNull($learnResult);     
    }

    protected function getLearnService()
    {
        return self::$kernel['learn_service'];
    }

    protected function getLearnDao()
    {
        return self::$kernel['learn_dao'];
    }

    protected function getKnowledgeDao()
    {
        return self::$kernel['knowledge_dao'];        
    }
}

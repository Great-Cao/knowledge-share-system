<?php

use Codeages\Biz\Framework\UnitTests\BaseTestCase;
use AppBundle\Common\ArrayToolKit;

class TopicServiceTest extends BaseTestCase
{
    public function testCreateTopic()
    {
        $topic = array(
            'name' => 'sql',
            'createdTime' => time(),
            'userId' => 1
        );
        $topic = $this->getTopicService()->createTopic($topic);
        $result = $this->getTopicService()->getTopicById(1, 1);

        $this->assertEquals($topic['name'], $result['name']);
        $this->assertEquals($topic['createdTime'], $result['createdTime']);
        $this->assertEquals($topic['userId'], $result['userId']);
    }

    public function testGetTopicById()
    {
        $topic = array(
            'name' => 'sql',
            'createdTime' => time(),
            'userId' => 1
        );
        $topic = $this->getTopicService()->createTopic($topic);
        $result = $this->getTopicService()->getTopicById(1, 1);

        $this->assertEquals($topic['name'], $result['name']);
        $this->assertEquals($topic['createdTime'], $result['createdTime']);
        $this->assertEquals($topic['userId'], $result['userId']);
    }

    public function testDeleteTopicById()
    {
        $topic = array(
            'name' => 'sql',
            'userId' => 1,
            'createdTime' => time(),
        );
        $user = array(
            'id' => 1,
        );
        $topic = $this->getTopicService()->createTopic($topic);
        $this->getTopicService()->deleteTopicById(1);
        $result = $this->getTopicService()->getTopicById($topic['id'], $user);

        $this->assertNotEquals($topic['id'],$result['id']);
    }

    public function testFindAllTopics()
    {
        $topic1 = array(
            'name' => 'sql',
            'createdTime' => time(),
            'userId' => '1'
        );
        $topic2 = array(
            'name' => 'sql1',
            'createdTime' => time(),
            'userId' => '2'
        );
        $topic3 = array(
            'name' => 'sql1',
            'createdTime' => time(),
            'userId' => '3'
        );
        $topic = $this->getTopicService()->createTopic($topic1);
        $topic = $this->getTopicService()->createTopic($topic2);
        $topic = $this->getTopicService()->createTopic($topic3);
        $result = ArrayToolKit::index($this->getTopicService()->findAllTopics($topic['id']),'id');

        $this->assertEquals(3, count($result));
    }

    public function testSearchTopics()
    {
        $topic = array(
            'name' => 'sql',
            'createdTime' => time(),
            'userId' => '1'
        );
        $topic = $this->getTopicService()->createTopic($topic);
        $result = ArrayToolKit::index($this->getTopicService()->findAllTopics($topic['id']),'id');

        $user['id'] = 1;
        $conditions = array(
            'userId' => $user['id'],
        );
        $orderBy = array('createdTime', 'ASC');
        $searchResult = ArrayToolKit::index($this->getTopicService()->searchTopics($conditions, $orderBy, 0, PHP_INT_MAX),'id');

        $this->assertEquals($topic['name'], $searchResult[1]['name']);
        $this->assertEquals($topic['createdTime'], $searchResult[1]['createdTime']);
        $this->assertEquals($topic['userId'], $searchResult[1]['userId']);
    }

    /**
     * @expectedException Exception
     */
    public function testDeleteTopicByIdWithException()
    {
        $topic = array(
            'name' => 'sql',
            'createdTime' => time(),
            'userId' => '1'
        );
        $topic = $this->getTopicService()->createTopic($topic);
        $this->getTopicService()->deleteTopicById('1');
        $this->getTopicService()->deleteTopicById(null);
    }

    /**
     * @expectedException Exception
     */
    public function testCreateTopicWithException()
    {
        $topic = array(
            'name' => 'sql',
            'createdTime' => time(),
            'userId' => '1'
        );
        $topic = $this->getTopicService()->createTopic(null);
    }

    protected function getTopicService()
    {
        return self::$kernel['topic_service'];
    }
}
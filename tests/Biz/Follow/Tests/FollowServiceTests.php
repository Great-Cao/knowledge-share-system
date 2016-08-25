<?php

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

class UserServiceTest extends BaseTestCase
{
    public function testFollowUser()
    {
        $followUser = array(
            'userId' => 1,
            'type' => 'user',
            'objectId' => 2
        );
        $this->getFollowService()->followUser(2);
        $status = $this->getFollowService()->getFollowUserByUserIdAndObjectUserId(1,2);
        $this->assertEquals($status,1);
    }

    public function testUnfollowUser()
    {
        $followUser = array(
            'userId' => 1,
            'type' => 'user',
            'objectId' => 2
        );
        $this->getFollowService()->followUser(2);
        $status0 = $this->getFollowService()->getFollowUserByUserIdAndObjectUserId(1,2);
        $this->getFollowService()->unfollowUser(2);
        $status1 = $this->getFollowService()->getFollowUserByUserIdAndObjectUserId(1,2);
        $this->assertEquals($status0,1);
        $this->assertEquals($status1,0);
    }

    public function testGetFollowTopicByUserIdAndTopicId()
    {   
        $follow = array(
            'objectId' => 1,
            'userId' => 1,
            'type' => 'topic',
        );
        $this->getFollowDao()->create($follow);
        $result = $this->getFollowService()->getFollowTopicByUserIdAndTopicId(1, 1);
        $this->assertEquals($result[0]['userId'], $follow['userId']);
        $this->assertEquals($result[0]['objectId'], $follow['objectId']);
        $this->assertEquals($result[0]['type'], $follow['type']);
    }

    public function testFollowTopic()
    {   
        $topic = array(
            'name' => '编程语言',
            'createdTime' => time(),
            'userId' => 1,
            'followNum' => 1,
        );
        $this->getTopicDao()->create($topic);
        $this->getFollowService()->followTopic(1);

        $result = $this->getFollowService()->getFollowTopicByUserIdAndTopicId(1, 1);

        $this->assertEquals(1, $result[0]['userId']);
        $this->assertEquals(1, $result[0]['objectId']);
    }

    public function testUnFollowTopic()
    {
        $topic = array(
            'name' => '编程语言',
            'createdTime' => time(),
            'userId' => 1,
            'followNum' => 1,
        );
        $this->getTopicDao()->create($topic);
        $this->getTopicDao()->create($topic);
        $this->getFollowService()->followTopic(2);
        $this->getFollowService()->unFollowTopic(2);

        $result = $this->getFollowService()->getFollowTopicByUserIdAndTopicId(1, 2);

        $this->assertEquals(array(), $result);
    }

    public function testFindFollowedTopics()
    {
        $topic = array(
            0 => array(
                'objectId' => 1,
                'userId' => 1,
                'type' => 'topic',
            ),
            1 => array(
                'objectId' => 2,
                'userId' => 1,
                'type' => 'topic',
            ),
            2 => array(
                'objectId' => 1,
                'userId' => 1,
                'type' => 'user',
            ),
        );
        $this->getFollowDao()->create($topic[0]);
        $this->getFollowDao()->create($topic[1]);
        $result = $this->getFollowService()->findFollowedTopics();

        $this->assertEquals(2, count($result));
        $this->assertEquals('1', $result[0]['id']);
        $this->assertEquals('2', $result[1]['id']);
    }

    public function testWaveFollowNum()
    {
        $topic = array(
            'name' => '编程语言',
            'createdTime' => time(),
            'userId' => 1,
            'followNum' => 1,
        );
        $this->getTopicDao()->create($topic);

        $ids = array(1);
        $diffs = array('followNum' => 1);
        $this->getFollowService()->waveFollowNum($ids, $diffs);
        $result = $this->getTopicDao()->get(1);

        $this->assertEquals(2, $result['followNum']);
    }

    /**
     * @expectedException Exception
     */
    public function testFollowTopicWithException()
    {
        $this->getFollowService()->followTopic(1);

        $result = $this->getFollowService()->getFollowTopicByUserIdAndTopicId(1, 1);

        $this->assertEquals(1, $result[0]['userId']);
        $this->assertEquals(1, $result[0]['objectId']);
    }

    /**
     * @expectedException Exception
     */
    public function testUnFollowTopicWithException()
    {
        $this->getFollowService()->followTopic(2);
        $this->getFollowService()->unFollowTopic(2);

        $result = $this->getFollowService()->getFollowTopicByUserIdAndTopicId(1, 2);

        $this->assertEquals(array(), $result);
    }

    protected function getFollowService()
    {
        return self::$kernel['follow_service'];
    }

    protected function getTopicDao()
    {
        return self::$kernel['topic_dao'];
    }

    protected function getFollowDao()
    {
        return self::$kernel['follow_dao'];
    }
}
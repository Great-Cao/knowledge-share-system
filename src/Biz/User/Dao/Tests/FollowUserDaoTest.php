<?php

namespace AppBundle\Service\User\Dao\Tests;

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

class UserDaoTest extends BaseTestCase
{

    public function testGetFollowUserByUserIdAndObjectUserId()
    {
        $follow = array(
            'userId' => 1,
            'type' => 'user',
            'objectId' => 2
        );

        $this->getFollowDao()->create($follow);
        $followUser = $this->getFollowDao()->getFollowUserByUserIdAndObjectUserId(1,2);
        $this->assertEquals($followUser['userId'], $follow['userId']);
        $this->assertEquals($followUser['objectId'], $follow['objectId']);
        $this->assertEquals($followUser['type'], $follow['type']);
    }

    protected function getFollowDao()
    {
        return self::$kernel['follow_dao'];
    }
}
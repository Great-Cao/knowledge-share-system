<?php

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

class UserDaoTest extends BaseTestCase
{
    public function testFindUsersByIds()
    {
        $userTest1 = array(
            'name' => '江南',
            'roles' => '游客',
            'picture' => 'lalallaal',
            'mobile' => '13567730096',
            'password' => '123456',
            'email' => '405954049@qq.com',
            'followNum' => '1',
            );
        $userTest2 = array(
            'name' => '南派',
            'roles' => '游客',
            'picture' => 'lalallaal',
            'mobile' => '13567730098',
            'password' => '1234567',
            'email' => '405954094@qq.com',
            'followNum' => '233',
            );
        $userTest1 = $this->getUserDao()->create($userTest1);
        $userTest2 = $this->getUserDao()->create($userTest2);
        $users = $this->getUserDao()->findByIds(array($userTest1['id'],$userTest2['id']));
        $this->assertEquals(count($users),2);
    }
    
    protected function getUserDao()
    {
        return self::$kernel['user_dao'];
    }
}
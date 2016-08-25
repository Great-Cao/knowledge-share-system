<?php
namespace Biz\User\Impl;

use Biz\User\UserService;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;

class UserServiceImpl extends KernelAwareBaseService implements UserService
{
    public function getUser($id)
    {
        return $this->getUserDao()->get($id);
    }

    public function findTopUsers($type)
    {
        $topConditions = array();
        if ($type == 'score') {
            $topOrderBy = array($type, 'DESC');
        } else {
            $topOrderBy = array($type.'Num', 'DESC');
        }
        $topNum = 5;
        $topUsers = $this->getUserDao()->search(
            $topConditions,
            $topOrderBy,
            0,
            $topNum
        );

        return $topUsers;
    }

    public function addScore($userId, $score)
    {
        $ids = array($userId);
        $diffs = array('score' => $score);

        return $this->getUserDao()->wave($ids, $diffs);
    }

    public function minusScore($userId, $score)
    {
        $ids = array($userId);
        $diffs = array('score' => $score);

        return $this->getUserDao()->wave($ids, $diffs);
    }

    public function findUsersByIds($ids)
    {
        return $this->getUserDao()->findUsersByIds($ids);
    }

    public function searchUsers($objectIds, $start, $limit)
    {
        return $this->getUserDao()->searchUsers($objectIds, $start, $limit);
    }

    public function getUserByUsername($username)
    {
        return $this->getUserDao()->getByUsername($username);
    }

    public function register($user)
    {
        $user['salt'] = md5(time().mt_rand(0, 1000));
        $user['password'] = $this->biz['password_encoder']->encodePassword($user['password'], $user['salt']);
        if (empty($user['roles'])) {
            $user['roles'] = array('ROLE_USER');
        }

        return $this->getUserDao()->create($user);
    }

    public function findUsers($conditions, $orderBy, $start, $limit)
    {
        return $this->getUserDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function getUsersCount($conditions)
    {
        return $this->getUserDao()->count($conditions);
    }

    protected function getUserDao()
    {
        return $this->biz['user_dao'];
    }
}
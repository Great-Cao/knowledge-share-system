<?php

namespace AppBundle\Controller;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Common\ArrayToolKit;
use AppBundle\Common\Paginator;

class FollowController extends BaseController
{
    public function indexAction(Request $request)
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser->isLogin()) {
           return $this->redirect($this->generateUrl("login"));
        }
        $follows = $this->getFollowService()->findFollowsByUserId($currentUser['id']);
        $followUsers = array();
        $followTopics = array();
        foreach ($follows as $follow) {
            if ($follow['type'] == 'user') {
                $followUsers[] = $follow;
            } else {
                $followTopics[] = $follow;
            }
        }
        $followUserIds = ArrayToolKit::column($followUsers, 'objectId');
        $followTopicIds = ArrayToolKit::column($followTopics, 'objectId');

        $paginator = new Paginator(
            $this->get('request'),
            $this->getKnowledgeService()->getFollowKnowledgesCount(array(
                'userIds' => $followUserIds,
                'topicIds' => $followTopicIds
            )),
            20
        );
        $followKnowledges = $this->getKnowledgeService()->searchFollowKnowledges(
            array(
                'userIds' => $followUserIds,
                'topicIds' => $followTopicIds
            ),
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        $users = $this->getUserService()->findUsersByIds(ArrayToolKit::column($followKnowledges, 'userId'));
        $users = ArrayToolKit::index($users, 'id');

        $topics = $this->getTopicService()->findTopicsByIds(ArrayToolKit::column($followKnowledges, 'topicId'));
        $topics = ArrayToolKit::index($topics, 'id');

        return $this->render('AppBundle:Follow:index.html.twig', array(
            'followKnowledges' => $followKnowledges,
            'users' => $users,
            'paginator' => $paginator,
            'topics' => $topics,
            'type' => 'followNotify'
        ));
    }

    protected function getUserService()
    {
        return $this->biz['user_service'];
    }

    protected function getFollowService()
    {
        return $this->biz['follow_service'];
    }

    protected function getKnowledgeService()
    {
        return $this->biz['knowledge_service'];
    }

    protected function getTopicService()
    {
        return $this->biz['topic_service'];
    }
}
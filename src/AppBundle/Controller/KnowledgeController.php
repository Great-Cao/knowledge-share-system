<?php

namespace AppBundle\Controller;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Common\ArrayToolKit;
use AppBundle\Common\Paginator;

class KnowledgeController extends BaseController
{
    public function indexAction($id)
    {
        $currentUser = $this->getCurrentUser();
        if (in_array('ROLE_SUPER_ADMIN', $currentUser['roles'])) {
            $userRole = array(
                'roles' => 'admin'
            );
        } else {
            $userRole = array(
                'roles' => 'user'
            );
        }

        $knowledge = $this->getKnowledgeService()->getKnowledge($id);
        $hasLearned = $this->getLearnService()->getLearnedByIdAndUserId($id, $currentUser['id']);

        $user = $this->getUserService()->getUser($knowledge['userId']);
        $conditions = array('knowledgeId' => $knowledge['id']);
        $orderBy = array('createdTime', 'DESC');
        $paginator = new Paginator(
            $this->get('request'),
            $this->getKnowledgeService()->getCommentsCount($conditions),
            20
        );
        $comments = $this->getKnowledgeService()->searchComments(
            $conditions,
            $orderBy,
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        $users = array();
        if (!empty($comments)) {
            $commentUserIds = ArrayToolKit::column($comments, 'userId');
            $commentUsers = $this->getUserService()->findUsersByIds(array_unique($commentUserIds));
            foreach ($commentUsers as $commentUser) {
                $users[$commentUser['id']] = $commentUser;
            }
        }

        $singleTagIds = $this->getTagService()->findTagsByIds(explode('|', $knowledge['tagId']));

        $knowledge = $this->getKnowledgeService()->isCorrectLink($knowledge);

        $knowledge = array($knowledge);
        $knowledge = $this->getFavoriteService()->hasFavoritedKnowledge($knowledge,$currentUser['id']);

        $knowledge = $this->getLikeService()->haslikedKnowledge($knowledge,$currentUser['id']);

        $likeList = $this->getLikeService()->findLikesByKnowledgeId($id);
        $likeUserIds = ArrayToolKit::column($likeList, 'userId');
        $likeUsers = array();
        foreach ($likeUserIds as $likeUserId) {
            $likeUsers[$likeUserId] = $this->getUserService()->getUser($likeUserId);
        }
        $favoriteList = $this->getFavoriteService()->findFavoritesByKnowledgeId($id);
        $favoriteUserIds = ArrayToolKit::column($favoriteList, 'userId');
        $favoriteUsers = array();
        foreach ($favoriteUserIds as $favoriteUserId) {
            $favoriteUsers[$favoriteUserId] = $this->getUserService()->getUser($favoriteUserId);
        }

        return $this->render('AppBundle:Knowledge:index.html.twig',array(
            'knowledge' => $knowledge[0],
            'user' => $user,
            'userRole' => $userRole,
            'comments' => $comments,
            'users' => $users,
            'paginator' => $paginator,
            'hasLearned' => $hasLearned,
            'singleTagIds' => $singleTagIds,
            'likeUsers' => $likeUsers,
            'favoriteUsers' => $favoriteUsers
        ));
    }

    public function createKnowledgeAction(Request $request)
    {
        $user = $this->getCurrentUser();
        $knowledge = $request->request->all();
        $knowledge['title'] = trim($knowledge['title']);
        $knowledge['topic'] = trim($knowledge['topic']);
        if (!isset($knowledge['tag'])) {
            $knowledge['tag'] = null;
        }

        if ($knowledge['type'] == 'file') {
            $file = $request->files->get('content');

            $tags = explode(",", trim($knowledge['tag']));
            $tagIds = $this->getKnowledgeService()->getTagIds($tags);

            $content = $this->getKnowledgeService()->moveToPath($file,$user,$knowledge);   
        } elseif ($knowledge['type'] == 'link') {
            $tagIds = $this->getKnowledgeService()->getTagIds($knowledge['tag']);
            $content = $request->request->get('content');
        }

        
        $topic = $this->getTopicService()->getTopicById($knowledge['topic'],$user);
        $data = array(
            'title' => $knowledge['title'],
            'summary' => $knowledge['summary'],
            'content' => $content,
            'topicId' => $topic['id'],
            'type' => $knowledge['type'],
            'userId' => $user['id'],
            'tagId' => $tagIds
        );
        $this->getKnowledgeService()->createKnowledge($data);
        $this->getUserService()->addScore($user['id'], 3);

        return new JsonResponse($data);
    }

    public function adminEditAction(Request $request, $id)
    {   
        if ($request->getMethod() == "POST") {
            $knowledge = $request->request->all();
            $this->getKnowledgeService()->updateKnowledge($id, $knowledge);
        }
        $knowledge = $this->getKnowledgeService()->getKnowledge($id);

        $tagIds = explode('|', $knowledge['tagId']);
        $tags = $this->getTagService()->findTagsByIds($tagIds);
        
        $topic = $this->getTopicService()->getTopicByKnowledgeId($knowledge['topicId']);

        return $this->render('AppBundle:Knowledge:admin-edit.html.twig', array(
            'knowledge' => $knowledge,
            'topic' => $topic,
            'tags' => $tags
        ));
    }

    public function adminDeleteAction(Request $request, $id)
    {   
        $this->getKnowledgeService()->deleteKnowledge($id);
        
        return new JsonResponse(true); 
    }

    public function createCommentAction(Request $request)
    {
        $currentUser = $this->getCurrentUser(); 
        if (!$currentUser->isLogin()) {
           return new JsonResponse(false);
        }
        $data = $request->request->all();
        $params = array(
            'value' => $data['comment'],
            'userId' => $currentUser['id'],
            'knowledgeId' => $data['knowledgeId']
        );
        $this->getKnowledgeService()->createComment($params);
        $knowledge = $this->getKnowledgeService()->getKnowledge($data['knowledgeId']);
        $this->getUserService()->addScore($currentUser['id'], 2);
        $this->getUserService()->addScore($knowledge['userId'], 3);

        return new JsonResponse(ture);
    }

    public function favoriteAction(Request $request, $id)
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser->isLogin()) {
            return new JsonResponse(array(
                'status' => 'false'
            ));
        }
        $this->getFavoriteService()->favoriteKnowledge($id, $currentUser['id']);
        $knowledge = $this->getKnowledgeService()->getKnowledge($id);
        $this->getUserService()->addScore($currentUser['id'], 1);
        $this->getUserService()->addScore($knowledge['userId'], 5);

        return new JsonResponse(array(
            'status' => 'success'
        ));
    }

    public function unfavoriteAction(Request $request, $id)
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser->isLogin()) {
            return new JsonResponse(array(
                'status' => 'false'
            ));
        }
        $this->getFavoriteService()->unfavoriteKnowledge($id, $currentUser['id']);
        $knowledge = $this->getKnowledgeService()->getKnowledge($id);
        $this->getUserService()->minusScore($currentUser['id'], -1);
        $this->getUserService()->minusScore($knowledge['userId'], -5);

        return new JsonResponse(array(
            'status' => 'success'
        ));

    }

    public function dislikeAction(Request $request, $id)
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser->isLogin()) {
            return new JsonResponse(array(
                'status' => 'false'
            ));
        }
        $this->getLikeService()->dislikeKnowledge($id, $currentUser['id']);
        $knowledge = $this->getKnowledgeService()->getKnowledge($id);
        $this->getUserService()->minusScore($currentUser['id'], -1);
        $this->getUserService()->minusScore($knowledge['userId'], -2);

        return new JsonResponse(array(
            'status' => 'success'
        ));

    }

    public function likeAction(Request $request, $id)
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser->isLogin()) {
            return new JsonResponse(array(
                'status' => 'false'
            ));
        }
        $this->getLikeService()->likeKnowledge($id, $currentUser['id']);
        $knowledge = $this->getKnowledgeService()->getKnowledge($id);
        $this->getUserService()->addScore($currentUser['id'], 1);
        $this->getUserService()->addScore($knowledge['userId'], 2);

        return new JsonResponse(array(
            'status' => 'success'
        ));
    }

    public function finishLearnAction(Request $request, $id)
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser->isLogin()) {
           return new JsonResponse(array(
            'status'=>'false'
        ));
        }
        $this->getLearnService()->finishKnowledgeLearn($id, $currentUser['id']);
        $knowledge = $this->getKnowledgeService()->getKnowledge($id);
        $this->getUserService()->addScore($currentUser['id'], 1);
        $this->getUserService()->addScore($knowledge['userId'], 1);

        return new JsonResponse(array(
            'status'=>'success'
        ));
    }

    public function downloadFileAction(Request $request, $id)
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser->isLogin()) {
           return $this->redirect($this->generateUrl("login"));;
        }
        $knowledge = $this->getKnowledgeService()->getKnowledge($id);
        $auth = $this->getUserService()->getUser($knowledge['userId']);

        $fileName = substr($knowledge['content'],0);
        $filePath = $_SERVER['DOCUMENT_ROOT'].'/files/'.$auth['username'].'/'.$fileName;

        if (!file_exists($filePath)) {
            throw new \Exception("文件不存在");
        }

        $fopen = fopen($filePath,"r+");
        $content = fread($fopen, filesize($filePath));

        $response = new Response();
        $response->headers->set('Content-type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');
        $response->setContent($content);
        fclose($fopen);
        return $response;
    }

    protected function getLikeService()
    {
        return $this->biz['like_service'];
    }

    protected function getKnowledgeService()
    {
        return $this->biz['knowledge_service'];
    }

    protected function getUserService()
    {
        return $this->biz['user_service'];
    }

    protected function getTopicService()
    {
        return $this->biz['topic_service'];
    }

    protected function getFavoriteService()
    {
        return $this->biz['favorite_service'];
    }

    protected function getFollowTopicService()
    {
        return $this->biz['follow_topic_service'];
    }

    protected function getLearnService()
    {
        return $this->biz['learn_service'];
    }

    protected function getTagService()
    {
        return $this->biz['tag_service'];
    }
}
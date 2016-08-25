<?php

namespace AppBundle\Controller;

use AppBundle\Common\Paginator;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Common\ArrayToolKit;
use Symfony\Component\Filesystem\Filesystem;

class MyKnowledgeShareController extends BaseController
{
    public function indexAction(Request $request)
    {
        $user = $this->getCurrentUser();
        if (!$user->isLogin()) {
           return $this->redirect($this->generateUrl("login"));
        }
        $fields = $request->query->all();
        $conditions = array(
            'userId' => $user['id'],
            'keyword' => '',
        );

        $conditions = array_merge($conditions, $fields);  
        if (isset($conditions['keyword'])) {
            $conditions['title'] = "%{$conditions['keyword']}%";
            unset($conditions['keyword']);
        }

        $paginator = new Paginator(
            $this->get('request'),
            $this->getKnowledgeService()->getKnowledgesCount($conditions),
            20
        );

        $knowledges = $this->getKnowledgeService()->searchKnowledges(
            $conditions,
            array('createdTime', 'DESC'),
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );
        
        return $this->render('AppBundle:MyKnowledgeShare:my-knowledge.html.twig',array(
            'knowledges' => $knowledges,
            'paginator' => $paginator,
            'type' => 'myKnowledge'
        ));
    }

    public function editAction(Request $request, $id)
    {
        if ($request->getMethod() == 'POST') {
            $knowledge = $request->request->all();
            $this->getKnowledgeService()->updateKnowledge($id, $knowledge);

            return $this->redirect($this->generateUrl('my_knowledge_share'));
        }

        $knowledge = $this->getKnowledgeService()->getKnowledge($id);

        return $this->render('AppBundle:MyKnowledgeShare:edit-knowledge.html.twig', array(
            'knowledge' => $knowledge
        ));
    }

    public function toDoListAction(Request $request)
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser->isLogin()) {
           return $this->redirect($this->generateUrl("login"));
        }
        $toDoList = $this->getToDoListService()->findToDoListByUserId($currentUser['id']);

        $paginator = new Paginator(
            $request,
            count($toDoList),
            20
        );

        $ids = ArrayToolKit::column($toDoList, 'knowledgeId');

        $knowledges = $this->getKnowledgeService()->searchKnowledgesByIds(
            $ids,
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );

        $users = $this->getUserService()->findUsersByIds(ArrayToolKit::column($knowledges, 'userId'));
        $users = ArrayToolKit::index($users, 'id');

        $knowledgeTags = array();
        foreach ($knowledges as $key => $knowledge) {
            $singleTagIds['knowledgeId'] = $knowledge['id'];
            $singleTagIds['knowledgeTag'] = $this->getTagService()->findTagsByIds(explode('|', $knowledge['tagId']));
            $knowledgeTags[] = $singleTagIds;
        }
        $knowledgeTags = ArrayToolKit::index($knowledgeTags, 'knowledgeId');

        return $this->render('AppBundle:MyKnowledgeShare:knowledge-todolist.html.twig', array(
            'knowledges' => $knowledges,
            'users' => $users,
            'paginator' => $paginator,
            'type' => 'toDoList',
            'knowledgeTags' => $knowledgeTags
        ));
    }

    public function deleteAction(Request $request, $id)
    {   
        $currentUser = $this->getCurrentUser();
        if (!$currentUser->isLogin()) {
           return $this->redirect($this->generateUrl("login"));;
        }
        $knowledge = $this->getKnowledgeService()->getKnowledge($id);
        $auth = $this->getUserService()->getUser($knowledge['userId']);
        $fileSystem = new Filesystem();
        if ($knowledge['type'] == 'file') {
            $filePath = $_SERVER['DOCUMENT_ROOT'].'/files/'.$auth['username'].'/'.$knowledge['content'];
            if (!file_exists($filePath)) {
                throw new \Exception("文件不存在");
            }
            $fileSystem->remove($filePath);
        }
        $this->getKnowledgeService()->deleteKnowledge($id);

        return new JsonResponse(true);
    }

    protected function getKnowledgeService()
    {
        return $this->biz['knowledge_service'];
    }

    protected function getToDoListService()
    {
        return $this->biz['todolist_service'];
    }

    protected function getUserService()
    {
        return $this->biz['user_service'];
    }

    protected function getTagService()
    {
        return $this->biz['tag_service'];
    }
}

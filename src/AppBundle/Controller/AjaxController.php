<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Common\Api;

class AjaxController  extends BaseController
{
    public function linkAction(Request $request)
    {
        $requestData = $request->request->all();
        $title = Api::getTitle($requestData['link']);

        return new JsonResponse(array(
            'title' => $title
        ));
    }

    public function topicAction(Request $request)
    {
        $conditions = $request->request->all();
        $conditions['name'] = "%{$conditions['name']}%";
        $topics = $this->getTopicService()->searchTopics(
            $conditions,
            array('createdTime', 'DESC'),
            0,
            PHP_INT_MAX
        );
        return new JsonResponse(array(
            'topics' => $topics
        ));
    }

    public function tagAction(Request $request)
    {
        $conditions = $request->request->all();

        $tags = $this->getTagService()->searchTags(
            $conditions,
            array('createdTime', 'DESC'),
            0,
            PHP_INT_MAX
        );

        return new JsonResponse(array(
            'tags' => $tags
        ));      
    }
    
    protected function getTopicService()
    {
        return $this->biz['topic_service'];
    }

    protected function getTagService()
    {
        return $this->biz['tag_service'];
    }

}
<?php

namespace AppBundle\Controller;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Common\CurlGet;

class TestController extends BaseController
{
    public function indexAction()
    {
        $url = "http://news.163.com/16/0809/01/BU07H77Q00014AED.html";
        $title = CurlGet::get($url);
        return new JsonResponse(array(
            'data' => $title
        ));
    }
}
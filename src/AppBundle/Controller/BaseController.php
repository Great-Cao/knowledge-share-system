<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Security\CurrentUser;

class BaseController extends Controller
{
    protected $biz;

    public function json($data = null, $status = 200, $headers = array())
    {
        return new JsonResponse($data, $status, $headers);
    }

    public function login($user, $request)
    {
        $currentUser = new CurrentUser($user);

        $token = new UsernamePasswordToken($currentUser, null, 'main', $currentUser->getRoles());
        $this->get('security.token_storage')->setToken($token);

        $event = new InteractiveLoginEvent($request, $token);
        $this->get('event_dispatcher')->dispatch('security.interactive_login', $event);
    }

    public function imagePathAction()
    {
        $user = $this->getCurrentUser();
        $user = $this->getUserService()->getUser($user['id']);
        $filePath = $_SERVER['DOCUMENT_ROOT'].'/picture/'.substr($user['imageUrl'], 8);
        if (!file_exists($filePath) || !is_file($filePath)) {
            $user['imageUrl'] = 'bundles/app/img/default-user-image.png';
        }
    
        return $this->render('AppBundle::header-user-image.html.twig', array(
            'user' => $user,
        ));
    }

    public function getCurrentUser()
    {
        return $this->biz->getCurrentUser();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->biz = $this->container->get('biz');
    }

    protected function getUserService()
    {
        return $this->biz['user_service'];
    }
}
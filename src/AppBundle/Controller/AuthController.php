<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends BaseController
{

    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'AppBundle:Auth:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );

    }

    public function logoutAction(Request $request)
    {

    }

    public function checkAction(Request $request)
    {

    }

    public function registerAction(Request $request)
    {
        if ('POST' == $request->getMethod()) {
            $user = $request->request->all();
            $user = $this->getUserService()->register($user);
            $this->login($user, $request);
            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('AppBundle:Auth:register.html.twig');
    }

    protected function getUserService()
    {
        return $this->biz['user_service'];
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: kaptan
 * Date: 16.08.2017
 * Time: 09:50
 */

namespace AppBundle\Security;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class CustomLoginAuthenticator extends AbstractGuardAuthenticator
{

    /**
     * @var Router
     */
    private $router;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager, Router $router)
    {
        $this->router = $router;
        $this->entityManager =  $entityManager;
    }

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/login' || !$request->isMethod('POST')) {
            return;
        }
        $formdata = $request->request->get("form");
        return [
            "userName" => $formdata["userName"],
            "password" => $formdata["password"],
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {

        return $userProvider->loadUserByUsername($credentials["userName"]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if ($credentials["password"] !== $user->getPassword()) {
            throw new CustomUserMessageAuthenticationException("Parolanız hatalı");
        }
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        $url = $this->router->generate("login");

        return new RedirectResponse($url);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $url = $this->router->generate("homepage");
        $formdata = $request->request->get("form");
        $request->getSession()->set("userName", $formdata["userName"]);
        $request->getSession()->set("LoggedIn", true);
        return new RedirectResponse($url);
    }

    public function supportsRememberMe()
    {
        return true;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $url = $this->router->generate("login");

        return new RedirectResponse($url);
    }
}
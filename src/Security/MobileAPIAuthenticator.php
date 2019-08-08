<?php

namespace App\Security;

use App\Entity\DeviceToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class MobileAPIAuthenticator extends AbstractGuardAuthenticator
{

    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function supports(Request $request)
    {
        return $request->headers->has("X-AUTH-TOKEN");
    }

    public function getCredentials(Request $request)
    {
        return array("token" => $request->headers->get("X-AUTH-TOKEN"));
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = $this
            ->entityManager
            ->getRepository(User::class)
            ->findOneByDeviceToken($credentials["token"]);

        if ($user == null) {
            throw new CustomUserMessageAuthenticationException("UInvalid token.");
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $deviceToken = $this->entityManager
                            ->getRepository(DeviceToken::class)
                            ->findOneByToken($credentials["token"]);

        if ($deviceToken->getValid()){
            return true;
        } else {
            throw new CustomUserMessageAuthenticationException("CInvalid token.");
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            "message" => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            "message" => "Authentication required."
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}

<?php

namespace App\Controller;

use App\Entity\DeviceToken;
use App\Entity\LockProperty;
use App\Entity\LockRequest;
use App\Entity\User;
use App\Service\LockRequestManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class MobileAPIController
 * @package App\Controller
 * @Route("/mobile-api", name="mobile_api_")
 */
class MobileAPIController extends AbstractController
{
    /**
     * @Route("/", name="available_methods", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     * @param RouterInterface $router
     * @return JsonResponse
     */
    public function available_methods(
        RouterInterface $router
    )
    {
        $data = [
            "endpoints" => []
        ];

        $routes = $router->getRouteCollection();

        foreach ($routes as $route) {

            $correctRoute = preg_match(
                "/^App\\\\Controller\\\\MobileAPIController.*/",
                $route->getDefault("_controller")
            );
            if ($correctRoute) {
                array_push($data["endpoints"],
                    [
                        "name" => $route->getPath(),
                        "methods" => $route->getMethods()
                    ]
                );
            }

        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/open", name="create_open_request", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     * @param UserInterface|User $user
     * @param EntityManagerInterface $em
     * @param LockRequestManager $lrm
     * @return JsonResponse
     */
    public function createOpenRequest(
        UserInterface $user,
        EntityManagerInterface $em,
        LockRequestManager $lrm
    )
    {
        $opened = $em
                ->getRepository(LockProperty::class)
                ->findOneByName("opened");

        $lr = $em
            ->getRepository(LockRequest::class);

        if ($lr->isAnyRequestPending(LockRequest::TYPE_OPEN)) {
            return new JsonResponse(
                ["message" => "Inny użytkownik zlecił już otwarcie drzwi"],
                Response::HTTP_OK
                );
        } else if ($opened->getConvertedValue()) {
            return new JsonResponse(
                ["message" => "Drzwi są już otwarte"],
                Response::HTTP_OK
            );
        } else {
            $request = $lrm->createOpenRequest($user);

            $data = [
                "requestID" => $request->getId(),
                "timesOpened" => $user->getTimesOpened()
            ];

            return new JsonResponse($data, Response::HTTP_CREATED);
        }
    }

    /**
     * @Route("/open/perm/{time}", name="create_open_perm_request", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param UserInterface $user
     * @param EntityManagerInterface $em
     * @param LockRequestManager $lrm
     * @param int $time
     * @return JsonResponse
     */
    public function createOpenPermRequest(
        UserInterface $user,
        EntityManagerInterface $em,
        LockRequestManager $lrm,
        int $time = -1
    )
    {
       $opened_perm = $em
           ->getRepository(LockProperty::class)
           ->findOneByName("opened_perm")
           ->getConvertedValue();

       $lr = $em->getRepository(LockRequest::class);

       if ($opened_perm) {
           return new JsonResponse([
               "message" => "Lock is already opened permanently; To change time, use /close endpoint"
           ], Response::HTTP_PRECONDITION_FAILED);
       } else if ($lr->isAnyRequestPending(LockRequest::TYPE_PERM_OPEN)) {
           return new JsonResponse([
               "message" => "There is another perm-open request pending"
           ], Response::HTTP_OK);
       } else {
           $request = $lrm->createOpenPermRequest($user, $time);

           $data = [
               "requestID" => $request->getId()
           ];

           return new JsonResponse($data, Response::HTTP_CREATED);
       }
    }

    /**
     * @Route("/close", name="create_close_request", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param UserInterface $user
     * @param EntityManagerInterface $em
     * @param LockRequestManager $lrm
     * @return JsonResponse
     */
    public function createCloseRequest(
        UserInterface $user,
        EntityManagerInterface $em,
        LockRequestManager $lrm
    )
    {
        $opened_perm = $em
            ->getRepository(LockProperty::class)
            ->findOneByName("opened_perm")
            ->getConvertedValue();

        $lr = $em->getRepository(LockRequest::class);

        if (!$opened_perm) {
            return new JsonResponse([
                "message" => "Lock is already closed"
            ], Response::HTTP_PRECONDITION_FAILED);
        } else if ($lr->isAnyRequestPending(LockRequest::TYPE_CLOSE)) {
            return new JsonResponse([
                "message" => "There is another close request pending"
            ], Response::HTTP_OK);
        } else {
            $request = $lrm->createCloseRequest($user);

            $data = [
                "requestID" => $request->getId()
            ];

            return new JsonResponse($data, Response::HTTP_CREATED);
        }
    }

    // TODO: createLockRequest()

    /**
     * @Route("/status/{id}", name="get_status", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     * @param UserInterface $user
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function getStatus(
        UserInterface $user,
        EntityManagerInterface $em,
        int $id
    )
    {
        $request = $em
            ->getRepository(LockRequest::class)
            ->findOneByID($id);

        if ($request == null) {
            return new JsonResponse([
                "message" => "Request with id ".strval($id)." does not exist"
            ], Response::HTTP_NOT_FOUND);
        }

        $this->denyAccessUnlessGranted("read", $request);

        $data = [
            "id" => $request->getId(),
            "type" => $request->getType(),
            "status" => $request->getStatus(),
            "user" => $request->getUser()->getId(),
            "timeCreated" => $request->getTimeAdded(),
            "timeUpdated" => $request->getTimeUpdated()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/token", name="invalidate_device_token", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER')")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return JsonResponse
     */
    public function invalidateDeviceToken(
        EntityManagerInterface $em,
        Request $request
    )
    {
        $deviceToken = $em
            ->getRepository(DeviceToken::class)
            ->findOneByToken($request->headers->get("X-AUTH-TOKEN"))
            ->setValid(false);

        $em->flush();

        return new JsonResponse([
            "message" => "Succesfuly invalidated token ".$deviceToken->getToken()
        ], Response::HTTP_OK);

    }
}

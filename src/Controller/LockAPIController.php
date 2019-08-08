<?php

namespace App\Controller;

use App\Entity\EntryTag;
use App\Entity\LocalEvent;
use App\Entity\LocalOpen;
use App\Entity\LockRequest;
use App\Service\LockRequestManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LockAPIController
 * @package App\Controller
 * @Route("/lock-api", name="lock_api_")
 */
class LockAPIController extends AbstractController
{

    /**
     * @Route("/update", name="update", methods={"GET"})
     * @param LockRequestManager $lrm
     * @return JsonResponse
     */
    public function update(LockRequestManager $lrm)
    {
        $request = $lrm->getPendingRequestOrNull();

        if ($request == null) {
            return new JsonResponse([
                "rp" => false
            ], Response::HTTP_OK);
        }

        $request->updateStatus(LockRequest::STATUS_DELIVERED);

        return new JsonResponse($request->getJSONObject(), Response::HTTP_OK);
    }

    /**
     * @Route("/report/{id}/{status}", name="report_request_status", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @param string $status
     * @return JsonResponse
     */
    public function reportRequestStatus(
        Request $request,
        EntityManagerInterface $entityManager,
        int $id,
        string $status)
    {
        $lockRequest = $entityManager->getRepository(LockRequest::class)
            ->findOneByID($id);

        if ($lockRequest == null) {
            return new JsonResponse([
                "message" => "Request ".strval($id)." doesn't exist"
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($lockRequest->getStatus() != LockRequest::STATUS_DELIVERED) {
            return new JsonResponse([
                "message" => "Request ".strval($id)." has invalid status"
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$request->request->has("status")) {
            return new JsonResponse([
                "message" => "Missing value: state"
            ], Response::HTTP_BAD_REQUEST);
        }

        $reportedStatus = $status;

        if (!in_array($reportedStatus, [LockRequest::STATUS_DONE, LockRequest::STATUS_FAIL])) {
            return new JsonResponse([
                "message" => "Invalid value of 'status'"
            ], Response::HTTP_BAD_REQUEST);
        }

        $lockRequest->updateStatus($reportedStatus);

        return new JsonResponse([], Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/report/event/{type}", name="report_event", methods={"POST"})
     * @param string $type
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function reportEvent(
        string $type,
        EntityManagerInterface $entityManager)
    {
        if (!LocalEvent::isSupported($type)) {
            return new JsonResponse([
                "message" => "Invalid type of event"
            ], Response::HTTP_BAD_REQUEST);
        }

        $le = new LocalEvent();
        $le ->setType($type);

        $entityManager->persist($le);

        return new JsonResponse([], Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/report/open/{id}", name="report_open", methods={"POST"})
     * @param EntityManagerInterface $entityManager
     * @param string $id
     * @return JsonResponse
     */
    public function reportOpen(
        EntityManagerInterface $entityManager,
        string $id)
    {
        if (!EntryTag::isTagIDValid($id)) {
            return new JsonResponse(
                ["message" => "ID is in invalid format"],
                Response::HTTP_BAD_REQUEST
            );
        }

        $updateFlag = false;

        $tag = $entityManager->getRepository(EntryTag::class)
            ->findOneByTagID($id);

        if ($tag == null) {
            $updateFlag = true;
            $tag = new EntryTag();
            $tag ->setType("unknown")
                ->setTagID($id);

            $entityManager->persist($tag);
        }

        $lo = new LocalOpen();
        $lo->setTagUsed($tag);
        $entityManager->persist($lo);

        return new JsonResponse(
            ["updateNeeded" => $updateFlag],
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/check/open/{id}", name="check_open", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @param string $id
     * @return JsonResponse
     */
    public function checkOpen(
        EntityManagerInterface $entityManager,
        string $id
    )
    {
        if (!EntryTag::isTagIDValid($id)) {
            return new JsonResponse(
                ["message" => "ID is in invalid format"],
                Response::HTTP_BAD_REQUEST
            );
        }

        $tag = $entityManager->getRepository(EntryTag::class)
            ->findOneByTagID($id);


        return new JsonResponse(
            ["allow" => $tag != null && $tag->getActive()],
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/fetch/db", name="fetch_database", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function fetchDatabase(
        EntityManagerInterface $entityManager
    )
    {
        $activeTags = $entityManager->getRepository(EntryTag::class)
            ->findByActiveness(true);

        $tags = [];

        foreach ($activeTags as $activeTag) {
            /**
             * @var $activeTag EntryTag
             */
            array_push($tags, $activeTag->getTagID());
        }

        return new JsonResponse(["tags" => $tags], Response::HTTP_OK);
    }
}




// TODO: design a way to report local openings

/**
 * 1) querying server each time someone has tried to open the door, open if server approves
 * 2) querying server as in 1), but with timout (when timeout is reached, lock will use it's local db)
 * 3*) synchronizing db between lock and server, using local db to check permissions, reporting to server after successful open
 */

/**
 * Lock can be disabled from reading cards or accepting phones
 */

/**
 * Locking the lock:
 *
 * 1) physical lock
 * 2) phone lock
 * 3) way to connect 1) and 2)
 *      1) markers in local db, updated with each LOCK LockRequest
 *      2) changing type of local opening to 1)  [****]
 */


/**
 * HOW TO GET THE ANIMATION TO DISPLAY WHEN LOCK IS LOCKED?
 * HOW TO GET THE ANIMATION TO DISPLAY WHEN LOCK RECEIVES LOCAL OPEN? IF AT ALL!!!
 */

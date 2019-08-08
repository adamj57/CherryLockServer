<?php


namespace App\Service;


use App\Entity\LockRequest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class LockRequestManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getPendingRequestOrNull()
    {
        $request = $this->entityManager->getRepository(LockRequest::class)
            ->findOneByStatus(LockRequest::STATUS_PENDING);

        return $request;
    }

    /**
     * @param UserInterface|User $user
     * @return LockRequest
     */
    public function createOpenRequest(UserInterface $user)
    {
        $request = new LockRequest();
        $request->setUser($user);
        $request->setType(LockRequest::TYPE_OPEN);
        $request->updateStatus(LockRequest::STATUS_PENDING);

        $user->incrementTimesOpened();

        $this->entityManager->persist($request);
        $this->entityManager->flush();

        return $request;
    }

    /**
     * @param UserInterface|User $user
     * @param int $maxTime
     * @return LockRequest
     */
    public function createOpenPermRequest(UserInterface $user, int $maxTime)
    {
        $request = new LockRequest();
        $request->setUser($user)
            ->setType(LockRequest::TYPE_PERM_OPEN)
            ->updateStatus(LockRequest::STATUS_PENDING)
            ->setData(json_encode([
                "time" => $maxTime
            ]));

        $this->entityManager->persist($request);
        $this->entityManager->flush();

        return $request;
    }

    /**
     * @param UserInterface|User $user
     * @return LockRequest
     */
    public function createCloseRequest(UserInterface $user)
    {
        $request = new LockRequest();
        $request->setUser($user)
            ->setType(LockRequest::TYPE_CLOSE)
            ->updateStatus(LockRequest::STATUS_PENDING);

        $this->entityManager->persist($request);
        $this->entityManager->flush();

        return $request;
    }
}
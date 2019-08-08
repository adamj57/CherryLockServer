<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LockRequestRepository")
 */
class LockRequest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="lockRequests")
     * @ORM\JoinColumn(nullable=false)
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $type;

    public const TYPE_OPEN = "open";
    public const TYPE_PERM_OPEN = "perm-open";
    public const TYPE_CLOSE = "close";

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $status;

    /**
     * Assigned when Request entity is created.
     */
    public const STATUS_CREATED = "created";

    /**
     * Assigned when Request entity is fully filled and ready to be dispached to the lock.
     */
    public const STATUS_PENDING = "pending";

    /**
     * Assigned when the lock reads update containing the Request.
     */
    public const STATUS_DELIVERED = "delivered";

    /**
     * Assigned when the lock flags the Request as done.
     */
    public const STATUS_DONE = "done";
    /**
     * Assigned when the lock flags the Request as failed.
     */
    public const STATUS_FAIL = "fail";

    /**
     * @ORM\Column(type="datetime")
     */
    private $timeAdded;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timeUpdated;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $data;


    public function __construct()
    {
        $this->status = self::STATUS_CREATED;
        $this->timeAdded = new \DateTime();
        $this->timeUpdated = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTimeAdded(): ?\DateTimeInterface
    {
        return $this->timeAdded;
    }

    public function setTimeAdded(\DateTimeInterface $timeAdded): self
    {
        $this->timeAdded = $timeAdded;

        return $this;
    }

    public function getTimeUpdated(): ?\DateTimeInterface
    {
        return $this->timeUpdated;
    }

    public function setTimeUpdated(\DateTimeInterface $timeUpdated): self
    {
        $this->timeUpdated = $timeUpdated;

        return $this;
    }

    public function updateStatus(string $status): self
    {
        $this->status = $status;
        $this->timeUpdated = new \DateTime();

        return $this;
    }

    public function getJSONObject()
    {
        switch ($this->type) {
            case self::TYPE_OPEN:
                return [
                    "rp" => true,
                    "rid" => $this->id,
                    "rt" => $this->type,
                    "ra" => $this->user->getAnimation()
                ];
            case self::TYPE_PERM_OPEN:
                if (!array_key_exists("time", $this->data)) {
                    throw new \LogicException("Key 'time' doesn't exist in data column!");
                }
                return [
                    "rp" => true,
                    "rid" => $this->id,
                    "rt" => $this->type,
                    "rti" => $this->data["time"]
                ];
            case self::TYPE_CLOSE:
                return [
                    "rp" => true,
                    "rid" => $this->id,
                    "rt" => $this->type
                ];

            default:
                throw new \LogicException("Invalid self-state!");
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

}

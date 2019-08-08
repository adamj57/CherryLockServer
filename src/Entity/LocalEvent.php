<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocalEventRepository")
 */
class LocalEvent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $type;

    public const TYPE_LOCAL_LOCK = "local-lock";
    public const TYPE_POWER_ON = "power-on";
    public const TYPE_FORCED_DISCONNECT = "forced-disconnect";
    public const TYPE_CONNECTION_ENABLED = "connection-enabled";

    public function __construct()
    {
        $this->time = new \DateTime();
    }

    public static function isSupported(string $type)
    {
        return in_array($type, [self::TYPE_LOCAL_LOCK, self::TYPE_POWER_ON, self::TYPE_FORCED_DISCONNECT,
                                self::TYPE_CONNECTION_ENABLED]);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}

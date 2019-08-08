<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocalOpenRepository")
 */
class LocalOpen
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EntryTag", inversedBy="localOpenings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tagUsed;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    public function __construct()
    {
        $this->time = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTagUsed(): ?EntryTag
    {
        return $this->tagUsed;
    }

    public function setTagUsed(?EntryTag $tagUsed): self
    {
        $this->tagUsed = $tagUsed;

        return $this;
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
}

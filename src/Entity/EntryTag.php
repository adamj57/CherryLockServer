<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntryTagRepository")
 */
class EntryTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $tagID;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="entryTags")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="LocalOpen", mappedBy="tagUsed", orphanRemoval=true)
     */
    private $localOpenings;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="integer")
     */
    private $timesOpened;

    public function __construct()
    {
        $this->localOpenings = new ArrayCollection();
        $this->active = true;
        $this->timesOpened = 0;
    }

    public static function isTagIDValid(string $tagID)
    {
        return preg_match("/^[0-9A-F]{8}$/", $tagID);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTagID(): ?string
    {
        return $this->tagID;
    }

    public function setTagID(string $tagID): self
    {
        $this->tagID = $tagID;

        return $this;
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

    /**
     * @return Collection|LocalOpen[]
     */
    public function getLocalOpenings(): Collection
    {
        return $this->localOpenings;
    }

    public function addLocalOpening(LocalOpen $localOpening): self
    {
        if (!$this->localOpenings->contains($localOpening)) {
            $this->localOpenings[] = $localOpening;
            $localOpening->setTagUsed($this);
        }

        return $this;
    }

    public function removeLocalOpening(LocalOpen $localOpening): self
    {
        if ($this->localOpenings->contains($localOpening)) {
            $this->localOpenings->removeElement($localOpening);
            // set the owning side to null (unless already changed)
            if ($localOpening->getTagUsed() === $this) {
                $localOpening->setTagUsed(null);
            }
        }

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getTimesOpened(): ?int
    {
        return $this->timesOpened;
    }

    public function setTimesOpened(int $timesOpened): self
    {
        $this->timesOpened = $timesOpened;

        return $this;
    }
}

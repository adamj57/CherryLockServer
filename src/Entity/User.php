<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pass;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\Column(type="boolean")
     */
    private $banned;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SchoolClass", mappedBy="users")
     */
    private $schoolClass;

    /**
     * @ORM\Column(type="integer")
     */
    private $timesOpened;

    public function __construct()
    {
        $this->schoolClass = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserame(): ?string
    {
        return $this->username;
    }

    public function setUserame(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function setPass(string $pass): self
    {
        $this->pass = $pass;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getBanned(): ?bool
    {
        return $this->banned;
    }

    public function setBanned(bool $banned): self
    {
        $this->banned = $banned;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return Collection|SchoolClass[]
     */
    public function getSchoolClass(): Collection
    {
        return $this->schoolClass;
    }

    public function addSchoolClass(SchoolClass $schoolClass): self
    {
        if (!$this->schoolClass->contains($schoolClass)) {
            $this->schoolClass[] = $schoolClass;
            $schoolClass->setUsers($this);
        }

        return $this;
    }

    public function removeSchoolClass(SchoolClass $schoolClass): self
    {
        if ($this->schoolClass->contains($schoolClass)) {
            $this->schoolClass->removeElement($schoolClass);
            // set the owning side to null (unless already changed)
            if ($schoolClass->getUsers() === $this) {
                $schoolClass->setUsers(null);
            }
        }

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

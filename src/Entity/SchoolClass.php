<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SchoolClassRepository")
 */
class SchoolClass
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $shortname;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="schoolClass")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SchoolClassType", inversedBy="schoolClasses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $schoolClassType;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    public function setShortname(string $shortname): self
    {
        $this->shortname = $shortname;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users): self
    {
        $this->users = $users;

        return $this;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setSchoolClass($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getSchoolClass() === $this) {
                $user->setSchoolClass(null);
            }
        }

        return $this;
    }

    public function getSchoolClassType(): ?SchoolClassType
    {
        return $this->schoolClassType;
    }

    public function setSchoolClassType(?SchoolClassType $schoolClassType): self
    {
        $this->schoolClassType = $schoolClassType;

        return $this;
    }

    public function getLabel(): ?string
    {
        return strval($this->year).$this->shortname;
    }
}

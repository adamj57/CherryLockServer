<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SchoolClassTypeRepository")
 */
class SchoolClassType
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SchoolClass", inversedBy="type")
     */
    private $schoolClasses;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSchoolClasses(): ?SchoolClass
    {
        return $this->schoolClasses;
    }

    public function setSchoolClasses(?SchoolClass $schoolClasses): self
    {
        $this->schoolClasses = $schoolClasses;

        return $this;
    }
}

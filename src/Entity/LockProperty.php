<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LockPropertyRepository")
 */
class LockProperty
{

    public const STRING = "string";
    public const INT = "int";
    public const BOOL = "boolean";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $value;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        if (is_bool($value)) {
            $this->value = $value ? "true" : "false";
        } else {
            $this->value = strval($value);
        }

        return $this;
    }

    public function getConvertedValue()
    {
        switch ($this->type)
        {
            case self::BOOL:
                return $this->value == "true" ? true : false;
            case self::INT:
                return intval($this->value);
            case self::STRING:
                return $this->value;
            default:
                return null; // shouldn't happen
        }
    }
}

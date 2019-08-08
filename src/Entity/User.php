<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="integer")
     */
    private $timesOpened = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SchoolClass", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $schoolClass;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeviceToken", mappedBy="user", orphanRemoval=true)
     */
    private $deviceTokens;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LockRequest", mappedBy="user", orphanRemoval=true)
     */
    private $lockRequests;

    /**
     * @ORM\Column(type="text")
     */
    private $animation = "ARQA/wA=";

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EntryTag", mappedBy="user")
     */
    private $entryTags;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\RegisterCode", mappedBy="user", cascade={"persist", "remove"})
     */
    private $registerCode;

    public function __construct()
    {
        $this->deviceTokens = new ArrayCollection();
        $this->lockRequests = new ArrayCollection();
        $this->entryTags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getTimesOpened(): ?int
    {
        return $this->timesOpened;
    }

    public function setTimesOpened(int $timesOpened): self
    {
        $this->timesOpened = $timesOpened;

        return $this;
    }

    public function incrementTimesOpened(): self
    {
        $this->timesOpened++;
        return $this;
    }

    public function getSchoolClass(): ?SchoolClass
    {
        return $this->schoolClass;
    }

    public function setSchoolClass(?SchoolClass $schoolClass): self
    {
        $this->schoolClass = $schoolClass;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return [$this->role];
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // does nothing; nothing sensitive is stored, even temporary
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function ban(): self
    {
        $this->role = ["ROLE_BANNED"];
        foreach ($this->entryTags as $entryTag) {
            /**
             * @var $entryTag EntryTag
             */
            $entryTag->setActive(false);
        }

        foreach ($this->deviceTokens as $deviceToken) {
            /**
             * @var $deviceToken DeviceToken
             */
            $deviceToken->setValid(false);
        }
        return $this;
    }

    /**
     * @return Collection|DeviceToken[]
     */
    public function getDeviceTokens(): Collection
    {
        return $this->deviceTokens;
    }

    public function addDeviceToken(DeviceToken $deviceToken): self
    {
        if (!$this->deviceTokens->contains($deviceToken)) {
            $this->deviceTokens[] = $deviceToken;
            $deviceToken->setUser($this);
        }

        return $this;
    }

    public function removeDeviceToken(DeviceToken $deviceToken): self
    {
        if ($this->deviceTokens->contains($deviceToken)) {
            $this->deviceTokens->removeElement($deviceToken);
            // set the owning side to null (unless already changed)
            if ($deviceToken->getUser() === $this) {
                $deviceToken->setUser(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|LockRequest[]
     */
    public function getLockRequests(): Collection
    {
        return $this->lockRequests;
    }

    public function addLockRequest(LockRequest $lockRequest): self
    {
        if (!$this->lockRequests->contains($lockRequest)) {
            $this->lockRequests[] = $lockRequest;
            $lockRequest->setUser($this);
        }

        return $this;
    }

    public function removeLockRequest(LockRequest $lockRequest): self
    {
        if ($this->lockRequests->contains($lockRequest)) {
            $this->lockRequests->removeElement($lockRequest);
            // set the owning side to null (unless already changed)
            if ($lockRequest->getUser() === $this) {
                $lockRequest->setUser(null);
            }
        }

        return $this;
    }

    public function getAnimation(): ?string
    {
        return $this->animation;
    }

    public function setAnimation(?string $animation): self
    {
        $this->animation = $animation;

        return $this;
    }

    /**
     * @return Collection|EntryTag[]
     */
    public function getEntryTags(): Collection
    {
        return $this->entryTags;
    }

    public function addEntryTag(EntryTag $entryTag): self
    {
        if (!$this->entryTags->contains($entryTag)) {
            $this->entryTags[] = $entryTag;
            $entryTag->setUser($this);
        }

        return $this;
    }

    public function removeEntryTag(EntryTag $entryTag): self
    {
        if ($this->entryTags->contains($entryTag)) {
            $this->entryTags->removeElement($entryTag);
            // set the owning side to null (unless already changed)
            if ($entryTag->getUser() === $this) {
                $entryTag->setUser(null);
            }
        }

        return $this;
    }

    public function getRegisterCode(): ?RegisterCode
    {
        return $this->registerCode;
    }

    public function setRegisterCode(?RegisterCode $registerCode): self
    {
        $this->registerCode = $registerCode;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $registerCode === null ? null : $this;
        if ($newUser !== $registerCode->getUser()) {
            $registerCode->setUser($newUser);
        }

        return $this;
    }
}

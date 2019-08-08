<?php


namespace App\Security;


use App\Entity\LockRequest;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class LockRequestVoter extends Voter
{

    private const READ = "read";
    private const EDIT = "edit";

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::READ])){
            return false;
        }

        if (!$subject instanceof LockRequest) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface|LockRequest $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted("ROLE_SUPER_ADMIN")) {
            return true;
        }

        switch ($attribute) {
            case self::READ:
                return $this->canRead($user, $subject);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canRead(?User $user, LockRequest $lockRequest)
    {
        if ($this->security->isGranted("ROLE_ADMIN")) {
            return true;
        }

        if ($user === $lockRequest->getUser()) {
            return true;
        }

        return false;
    }


}
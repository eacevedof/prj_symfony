<?php
//src/Security/Authorization/Voter/CategoryVoter.php
declare(strict_types=1);

namespace App\Security\Authorization\Voter;

use App\Entity\Category;
use App\Entity\User;
use App\Security\Roles;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CategoryVoter extends BaseVoter
{
    // api_platform/resource yaml: security: 'is_granted("CATEGORY_READ")'
    private const CATEGORY_READ = 'CATEGORY_READ';
    private const CATEGORY_CREATE = 'CATEGORY_CREATE';
    private const CATEGORY_UPDATE = 'CATEGORY_UPDATE';
    private const CATEGORY_DELETE = 'CATEGORY_DELETE';

    /**
     * {@inheritdoc}
     */
    protected function supports(string $attribute, $subject): bool
    {
        return \in_array($attribute, $this->getSupportedAttributes(), true);
    }

    /**
     * @param Category|null $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $tokenUser */
        $tokenUser = $token->getUser();

        if (self::CATEGORY_READ === $attribute) {
            if (null === $subject) {
                return $this->security->isGranted(Roles::ROLE_ADMIN);
            }

            if (null !== $subject->getGroup()) {
                return $this->security->isGranted(Roles::ROLE_ADMIN)
                    || $this->groupRepository->userIsMember($subject->getGroup(), $tokenUser);
            }

            return $this->security->isGranted(Roles::ROLE_ADMIN)
                || $subject->isOwnedBy($tokenUser);
        }

        if (self::CATEGORY_CREATE === $attribute) {
            return true;
        }

        if (\in_array($attribute, [self::CATEGORY_UPDATE, self::CATEGORY_DELETE])) {
            if (null !== $subject->getGroup()) {
                return $this->security->isGranted(Roles::ROLE_ADMIN)
                    || $this->groupRepository->userIsMember($subject->getGroup(), $tokenUser);
            }

            return $this->security->isGranted(Roles::ROLE_ADMIN)
                || $subject->isOwnedBy($tokenUser);
        }

        return false;
    }

    private function getSupportedAttributes(): array
    {
        return [
            self::CATEGORY_READ,
            self::CATEGORY_CREATE,
            self::CATEGORY_UPDATE,
            self::CATEGORY_DELETE,
        ];
    }
}
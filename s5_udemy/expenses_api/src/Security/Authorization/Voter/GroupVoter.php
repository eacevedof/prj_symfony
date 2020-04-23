<?php
// src/Security/Authorization/Voter/GroupVoter.php
declare(strict_types=1);
namespace App\Security\Authorization\Voter;

use App\Entity\Group;
use App\Entity\User;
use App\Security\Roles;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class GroupVoter extends BaseVoter
{
    private const GROUP_READ = "GROUP_READ";
    private const GROUP_CREATE = "GROUP_CREATE";
    private const GROUP_UPDATE = "GROUP_UPDATE";
    private const GROUP_DELETE = "GROUP_DELETE";

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, $subject):bool
    {
        return \in_array($attribute,$this->getSupportedAttributes(),true);
    }

    /**
     * @param Group|null $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token):bool
    {
        /** @var User $tokenUser */
        $tokenUser = $token->getUser();

        if(self::GROUP_READ === $attribute)
        {
            //si no hay grupo
            if(null === $subject)
            {
                //comprueba si el usuario en sesion (token) es admin
                return $this->security->isGranted(Roles::ROLE_ADMIN);
            }

            return $this->security->isGranted(Roles::ROLE_ADMIN) || $this->groupRepository->userIsMember($subject, $tokenUser);
        }

        //si llega a este punto de ejecución es porque el usuario tiene piermisos y puede crear un grupo
        if( self::GROUP_CREATE === $attribute )
        {
            return true;
        }

        //cualquier administrador o cualquier miembro del grupo
        if(self::GROUP_UPDATE === $attribute)
        {
            return $this->security->isGranted(Roles::ROLE_ADMIN) || $this->groupRepository->userIsMember($subject, $tokenUser);
        }

        if (self::GROUP_DELETE === $attribute) {
            return $this->security->isGranted(Roles::ROLE_ADMIN)
                || $subject->isOwnedBy($tokenUser);
        }

        return false;
    }//voteOnAttribute

    private function getSupportedAttributes():array
    {
        return [
            self::GROUP_READ,
            self::GROUP_CREATE,
            self::GROUP_UPDATE,
            self::GROUP_DELETE
        ];
    }

}// GroupVoter
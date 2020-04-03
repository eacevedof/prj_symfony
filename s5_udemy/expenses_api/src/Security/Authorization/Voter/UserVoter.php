<?php

declare(strict_types=1);
namespace App\Security\Authorization\Voter;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\User;
use App\Security\Roles;

/**
 * Class UserVoter
 * Cuando se intente hacer una operación sobre un recurso se van a lanzar todos los metodos del Voter
 * y se verá cual de ellos soporta el atributo (operacion R,U,D) que se está pasando
 */
class UserVoter extends BaseVoter
{

    public const USER_READ = "";
    public const USER_UPDATE = "";
    public const USER_DELETE = "";

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, $subject): bool
    {
        //en base a un atributo READ,UPDATE,DELETE y la entidad devolverá si es valido o no
        return \in_array($attribute,$this->getSupportedAttributes(),true);
    }

    /**
     * @inheritDoc
     * @param string $attribute se obtiene de is_granted('USER_READ',object) y es USER_READ
     * @param User | null $subject es el "object" que es un objeto usuario, null para casos is_granted(ACCION)
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $tokenUser  **/
        $tokenUser = $token->getUser();
        if(self::USER_READ === $attribute){
            //si no hay entidad la lectura escritura solo se permitirá a los admin
            if(null === $subject){
                $this->security->isGranted(Roles::ROLE_ADMIN);
            }

            return ($this->security->isGranted(Roles::ROLE_USER) || $subject->equals($tokenUser));
        }

        if(\in_array($attribute, [self::USER_UPDATE, self::USER_DELETE]))
        {
            return ($this->security->isGranted(Roles::ROLE_USER) || $subject->equals($tokenUser));
        }

        return false;
    }

    private function getSupportedAttributes():array
    {
        return [
            self::USER_READ,
            self::USER_UPDATE,
            self::USER_DELETE
        ];
    }

}
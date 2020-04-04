<?php
//src/Security/Authorization/Voter/UserVoter.php
declare(strict_types=1);
namespace App\Security\Authorization\Voter;

use App\Entity\User;
use App\Security\Roles;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class UserVoter
 * Cuando se intente hacer una operación sobre un recurso se van a lanzar todos los metodos del Voter
 * y se verá cual de ellos soporta el atributo (operacion R,U,D) que se está pasando
 */
class UserVoter extends BaseVoter
{

    public const USER_READ = "USER_READ";
    public const USER_UPDATE = "USER_UPDATE";
    public const USER_DELETE = "USER_DELETE";

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
                return $this->security->isGranted(Roles::ROLE_ADMIN);
            }

            //si el token tiene ROLE_ADMIN o el registro a modificar es igual a la entidad que está moodificando
            return ($this->security->isGranted(Roles::ROLE_ADMIN) || $subject->equals($tokenUser));
        }

        //si se pretende actualizar o borrar
        if(\in_array($attribute, [self::USER_UPDATE, self::USER_DELETE]))
        {
            //el token (usuario en sesion) debe ser admin o debe ser el mismo
            return ($this->security->isGranted(Roles::ROLE_ADMIN) || $subject->equals($tokenUser));
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

/*
vendor/symfony/security-core/Authorization/AuthorizationChecker.php

final public function isGranted($attribute, $subject = null): bool
{
    if (null === ($token = $this->tokenStorage->getToken())) {
        throw new AuthenticationCredentialsNotFoundException('The token storage contains no authentication token. One possible reason may be that there is no firewall configured for this URL.');
    }

    if ($this->alwaysAuthenticate || !$token->isAuthenticated()) {
        $this->tokenStorage->setToken($token = $this->authenticationManager->authenticate($token));
    }

    //hay varios metodos de decision y cada uno compruega algo, el atributo y el token pasan por los voters
    //vendor/symfony/security-core/Authorization/AccessDecisionManager.php
    return $this->accessDecisionManager->decide($token, [$attribute], $subject);
}
*/
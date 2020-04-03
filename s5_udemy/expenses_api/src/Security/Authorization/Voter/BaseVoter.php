<?php
//src/Security/Authorization/Voter/BaseVoter.php
declare(strict_types=1);
namespace App\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/*
 * La clase abstracta nos va a permitir que los hijos hereden de BaseVoter y que
 * puedan customizar los metodos obligatorios y la interfaz no obliga a la clase abstracta
 * a implementar los metodos de la interfaz
 * */
abstract class BaseVoter extends Voter //Voter implementa: VoterInterface
{

    protected Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

}
<?php
//src/Security/Validator/Role/CanAddRoleAdmin.php
declare(strict_types=1);
namespace App\Security\Validator\Role;

use App\Api\Action\RequestTransformer;
use App\Exceptions\Role\RequiredRoleToAddRoleAdminNotFoundException;
use App\Exceptions\Role\UnsupportedRoleException;
use App\Security\Roles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

/**
 * Class CanAddRoleAdmin
 * @package App\Security\Validator\Role
 * Si un usuario es admin puede asignarle rol de admin a otro usuario de lo contrario no
 */
class CanAddRoleAdmin implements RoleValidator
{

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public function validate(Request $request): array
    {
        $roles = \array_unique(RequestTransformer::getRequiredField($request,"roles"));

        if( \in_array(Role::ROLE_ADMIN, $roles, true))
        {
            if(!$this->security->isGranted(Role::ROLE_ADMIN))
            {
                throw RequiredRoleToAddRoleAdminNotFoundException::fromRole(Roles::ROLE_ADMIN);
            }
        }

        return $roles;
    }
}
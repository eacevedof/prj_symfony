<?php
//src/Exceptions/Role/RequiredRoleToAddRoleAdminNotFoundException.php
declare(strict_types=1);
namespace App\Exceptions\Role;

//Rol requerido para añadir rol admin no encontrado
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequiredRoleToAddRoleAdminNotFoundException extends BadRequestHttpException
{
    private const MESSAGE = "%s required to perform this operation";

    public static function fromRole(string $role): self
    {
        //__construct(string $message = null, \Throwable $previous = null, int $code = 0, array $headers = [])
        throw new self(\sprintf(self::MESSAGE,$role));
    }
}
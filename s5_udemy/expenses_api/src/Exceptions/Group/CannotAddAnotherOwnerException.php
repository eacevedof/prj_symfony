<?php
//src/Exceptions/Group/CannotAddAnotherOwnerException.php
declare(strict_types=1);

namespace App\Exceptions\Group;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotAddAnotherOwnerException extends AccessDeniedHttpException
{
    private const MESSAGE = 'You cannot add another user as owner';

    public static function create(): self
    {
        throw new self(self::MESSAGE);
    }
}

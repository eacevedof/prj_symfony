<?php
// src/Exceptions/User/UserAlredyMemberOfGroupException.php
declare(strict_types=1);

namespace App\Exceptions\User;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserAlredyMemberOfGroupException extends ConflictHttpException
{
    private const MESSAGE = 'User with id %s is already member of the group';

    public static function fromUserId(string $id): self
    {
        throw new self(\sprintf(self::MESSAGE, $id));
    }
}

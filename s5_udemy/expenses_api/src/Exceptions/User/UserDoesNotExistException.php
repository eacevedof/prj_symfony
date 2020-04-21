<?php
// src/Exceptions/User/UserDoesNotExistException.php
declare(strict_types=1);

namespace App\Exceptions\User;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserDoesNotExistException extends ConflictHttpException
{
    private const MESSAGE = 'User with id %s does not exist';

    public static function fromUserId(string $id): self
    {
        throw new self(\sprintf(self::MESSAGE, $id));
    }
}

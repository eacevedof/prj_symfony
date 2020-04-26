<?php
// src/Exceptions/Group/UserNotMemberOfGroupException.php
declare(strict_types=1);
namespace App\Exceptions\Group;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserNotMemberOfGroupException extends BadRequestHttpException
{
    private const MESSAGE = 'User not member of this group';

    public static function create(): self
    {
        throw new self(self::MESSAGE);
    }
}//UserNotMemberOfGroupException
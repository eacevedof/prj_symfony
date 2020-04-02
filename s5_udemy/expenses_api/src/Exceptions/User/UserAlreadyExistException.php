<?php

declare(strict_types=1);
namespace App\Exceptions\User;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserAlreadyExistException extends BadRequestHttpException
{
    private const MESSAGE = "User with email %s already exists";

    public static function fromUserEmail(string $email):self
    {
        throw new self(\sprintf(self::MESSAGE,$email));
    }
}
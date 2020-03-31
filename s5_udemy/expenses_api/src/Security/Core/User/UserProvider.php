<?php

declare(strict_types=1);

namespace App\Security\Core\User;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    public function loadUserByUsernameAndPayload($usuername, array $payload): UserInterface
    {
        return $this->findUser($username);
    }

    private function findUser(string $username): User
    {
        $user = $this->userRepository->findOneByEmail($username);
        if (null === $user) {
            throw new UsernameNotFoundException(\sprintf('User with email %s not found!', $username));
        }

        return $user;
    }

    public function loadUserByUsername(string $username)
    {
        return $this->findUser($username);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(\sprintf('Instances of %s are not supported', \get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        $user->setPassword($newEncodedPassword);
        $this->userRepository($user);
    }

    public function supportsClass(string $class): bool
    {
        return User::class == $class;
    }
}

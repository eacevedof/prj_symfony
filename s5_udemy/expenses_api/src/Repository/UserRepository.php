<?php
// src/Repository/UserRepository.php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

class UserRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return User::class;
    }

    public function findOneById(string $id): ?User
    {
        /**
         * @var User $user
         */
        $user = $this->objectRepository->find($id);
        return $user;
    }

    public function findOneByEmail(string $email): ?User
    {
        /** @var User $user */
        $user = $this->objectRepository->findOneBy(['email' => $email]);

        return $user;
    }

    public function save(User $user): void
    {
        $this->saveEntity($user);
    }


}

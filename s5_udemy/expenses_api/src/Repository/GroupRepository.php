<?php
//src/Repository/GroupRepository.php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Group;
use App\Entity\User;

class GroupRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return Group::class;
    }

    public function findOneById(string $id): ?Group
    {
        /** @var Group $group */
        $group = $this->objectRepository->find($id);

        return $group;
    }

    public function userIsMember(Group $group, User $user): bool
    {
        foreach ($group->getUsers() as $userGroup) {
            if ($userGroup->getId() === $user->getId()) {
                return  true;
            }
        }

        return false;
    }
}


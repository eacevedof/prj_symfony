<?php
//src/Doctrine/Extension/DoctrineUserExtension.php
declare(strict_types=1);

namespace App\Doctrine\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Category;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;
use App\Security\Roles;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class DoctrineUserExtension implements QueryCollectionExtensionInterface
{
    private TokenStorageInterface $tokenStorage;

    private Security $security;

    private GroupRepository $groupRepository;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        Security $security,
        GroupRepository $groupRepository
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->security = $security;
        $this->groupRepository = $groupRepository;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $qb, string $resourceClass): void
    {
        if ($this->security->isGranted(Roles::ROLE_ADMIN)) {
            return;
        }

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $rootAlias = $qb->getRootAliases()[0];

        if (Group::class === $resourceClass) {
            $qb->andWhere(\sprintf('%s.%s = :currentUser', $rootAlias, $this->getResources()[$resourceClass]));
            $qb->setParameter(':currentUser', $user);
        }

        if (Category::class === $resourceClass) {
            $parameterId = '';
            if (null !== $qb->getParameters()[0]) {
                $parameterId = $qb->getParameters()[0]->getValue();
            }

            if ($this->isGroupAndUserIsMember($parameterId, $user)) {
                $qb->andWhere(\sprintf('%s.group = :parameterId', $rootAlias));
                $qb->setParameter('parameterId', $parameterId);
            } else {
                $qb->andWhere(\sprintf('%s.%s = :currentUser', $rootAlias, $this->getResources()[$resourceClass]));
                $qb->andWhere(\sprintf('%s.group IS NULL', $rootAlias));
                $qb->setParameter(':currentUser', $user);
            }
        }

    }// addWhere()

    private function getResources(): array
    {
        return [
            Group::class => 'owner',  //tabla user_group.owner_id
            Category::class => "user", //tabla category.user_id
        ];
    }

    private function isGroupAndUserIsMember(string $parameterId, User $user): bool
    {
        if (null !== $group = $this->groupRepository->findOneById($parameterId)) {
            return $this->groupRepository->userIsMember($group, $user);
        }

        return false;
    }
}//DoctrineUserExtension
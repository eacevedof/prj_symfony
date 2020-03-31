<?php

declare(strict_types=1);

namespace App\Repository;

//esta clase aparece como obsoleta pero en esta version de doctrine no esta del todo corregida
//por lo tanto la seguimos usando
//use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

abstract class BaseRepository
{
    protected ManagerRegistry $managerRegistry;
    protected ObjectRepository $objectRepository;
    protected Connection $connection;

    public function __construct(ManagerRegistry $managerRegistry, Connection $connection)
    {
        $this->managerRegistry = $managerRegistry;
        $this->connection = $connection;

        //getRepository devolverá el repositorio del nombre de la entidad que se le inyecta
        $this->objectRepository = $this->getEntityManager()->getRepository($this->entityClass());
    }

    abstract protected static function entityClass(): string;

    protected function saveEntity($entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    protected function removeEntity($entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    protected function createQueryBuilder(): QueryBuilder
    {
        return $this->getEntityManager()->createQueryBuilder();
    }

    /**
     * @throws DBALException
     *                       para hacer queries abiertas
     */
    protected function executeFetchQuery(string $query, array $params = []): array
    {
        return $this->connection->executeQuery($query, $params)->fetchAll();
    }

    /**
     * @throws DBALException
     */
    protected function executeInsertQuery(string $query, array $params = []): array
    {
        return $this->connection->executeQuery($query, $params);
    }

    private function getEntityManager(): ObjectManager
    {
        $entityManager = $this->managerRegistry->getManager();
        if ($entityManager->isOpen()) {
            return $entityManager;
        }

        return $this->managerRegistry->resetManager();
    }
}

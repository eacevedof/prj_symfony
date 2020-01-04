<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    public function findByRaza()
    {
        $qb = $this->createQueryBuilder("a")
                //->andWhere("a.raza",$raza)
                //->orderBy("a.id","DESC")
                ->orderBy("a.tipo","DESC")
                ->getQuery();
        $result = $qb->execute();
        return $result;        
    }
    
//      da error!!!
      public function findAllAnimals(){
        $qb = $this->createQueryBuilder("a")
                ->orderBy("a.id","DESC")
                ->getQuery();
        $result = $qb->execute();
        return $result;
    }
    
}//class AnimalRepository

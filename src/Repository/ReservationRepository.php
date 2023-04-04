<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function save(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   public function findByUser($id): array
   {
       return $this->createQueryBuilder('r')
           ->Where('r.refugee = :id')
           ->setParameter('id', $id)
        //    ->orderBy('r.beginDate', 'ASC')
        //    ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   public function findByUserUpcoming($id): array
   {
            return $this->createQueryBuilder('r')
            ->Where('r.refugee = :id')
            ->andWhere('r.beginDate >= :today')
            ->setParameter('id', $id)
            ->setParameter('today', new \DateTime())  
                //    ->orderBy('r.beginDate', 'ASC')
                //    ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   public function findByUserPassed($id): array
   {
        return $this->createQueryBuilder('r')
        ->Where('r.refugee = :id')
        ->andWhere('r.endDate < :today')
        ->setParameter('id', $id)
        ->setParameter('today', new \DateTime())  
            //    ->orderBy('r.beginDate', 'ASC')
            //    ->setMaxResults(10)
        ->getQuery()
        ->getResult();
   }

   public function findByHostUpcoming($id): array
   {
            return $this->createQueryBuilder('r')
            ->Where('r.host = :id')
            ->andWhere('r.beginDate >= :today')
            ->setParameter('id', $id)
            ->setParameter('today', new \DateTime())  
                //    ->orderBy('r.beginDate', 'ASC')
                //    ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   public function findByHostPassed($id): array
   {
        return $this->createQueryBuilder('r')
        ->Where('r.host = :id')
        ->andWhere('r.endDate < :today')
        ->setParameter('id', $id)
        ->setParameter('today', new \DateTime())  
            //    ->orderBy('r.beginDate', 'ASC')
            //    ->setMaxResults(10)
        ->getQuery()
        ->getResult();
   }

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

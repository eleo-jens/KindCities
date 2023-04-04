<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 *
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function save(Service $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Service $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @return Service[] Returns an array of Service objects
     */
    public function findByFilters($idCategorie, $from, $to): array
    {
        $result = $this->createQueryBuilder('s')
            ->andWhere(':idCategorie is NULL or s.categorie = :idCategorie')
            ->setParameter(':idCategorie', $idCategorie)
            // ici j'aimerais aussi faire en sorte que les deux dates puissent entre null mais pas juste une seule ?
            ->Join('s.disponibilites', 'd')
            ->andWhere(':to BETWEEN d.beginDateDispo AND d.endDateDispo')
            ->setParameter('to', $to)
            ->andWhere(':from BETWEEN d.beginDateDispo AND d.endDateDispo')
            ->setParameter('from', $from)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function findAllWithDisponibilites(): array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.disponibilites', 'd')
            ->addSelect('d')
         //    ->orderBy('d.id', 'ASC')
         //    ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Service[] Returns an array of Service objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Service
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

<?php

namespace App\Repository;

use App\Entity\Affectations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Affectation>
 *
 * @method Affectations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Affectations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Affectations[]    findAll()
 * @method Affectations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AffectationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affectations::class);
    }

    public function save(Affectations $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Affectations $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByExampleField($value): array
   {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findByMembre($membreId)
{
    return $this->createQueryBuilder('a')
        ->join('a.terrain', 't')
        ->join('t.Membre', 'm')
        ->andWhere('m.id = :membreId')
        ->setParameter('membreId', $membreId)
        ->getQuery()
        ->getResult();
}
//    /**
//     * @return Affectation[] Returns an array of Affectation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Affectation
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\Association;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Association>
 *
 * @method Association|null find($id, $lockMode = null, $lockVersion = null)
 * @method Association|null findOneBy(array $criteria, array $orderBy = null)
 * @method Association[]    findAll()
 * @method Association[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssociationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Association::class);
    }

    public function save(Association $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Association $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function search($query)
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.nom LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('a.nom', 'ASC')
            ->getQuery();

        return $qb->getResult();
    }
    public function reset()
    {
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.nom', 'ASC')
            ->getQuery();

        return $qb->getResult();
    }
}
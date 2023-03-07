<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;


/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
   
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
      
    }

    public function save(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findPopularPosts()
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT p, COUNT(r) as HIDDEN rating_count, SUM(CASE r.rate WHEN 1 THEN 1 ELSE -1 END) as HIDDEN score
         FROM App\Entity\Post p
         LEFT JOIN p.ratings r
         GROUP BY p.id
         ORDER BY score DESC'
    );

    return $query->getResult();
}

public function getCommentsStats()
{
    $qb = $this->createQueryBuilder('p')
        ->select('p.nom', 'COUNT(c.id) as commentsCount')
        ->leftJoin('p.commentaires', 'c')
        ->groupBy('p.id')
        ->getQuery();

    return $qb->getResult();
}



// ...

public function createQueryBuilderForSearch(string $query = null, string $date = null): QueryBuilder
{
    $qb = $this->createQueryBuilder('p');

    if ($query !== null) {
        $qb->andWhere('p.nom LIKE :query')
            ->setParameter('query', '%'.$query.'%');
    }

    if ($date !== null) {
        $dateObj = \DateTime::createFromFormat('Y-m-d', $date);
        if ($dateObj !== false) {
            $qb->andWhere('p.date_Creation >= :date AND p.date_Creation <= :endDate')
                ->setParameter('date', $dateObj->format('Y-m-d 00:00:00'))
                ->setParameter('endDate', $dateObj->format('Y-m-d 23:59:59'));
        }
    }

    return $qb;
}






/**
 * @param string $query
 * @param DateTimeInterface|null $date
 * @return PaginationInterface
 */

//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

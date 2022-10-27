<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offer>
 *
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Offer::class);
    }

    public function save(Offer $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Offer $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllPaginated() {
        return $this->createQueryBuilder('o')
            ->orderBy('o.createdAt', 'DESC');
    }


    private function getOfferQueryBuilder(): QueryBuilder {
        // Select the orders and their packages
        $queryBuilder = $this->createQueryBuilder('o');

        //Return the QueryBuilder
        return $queryBuilder;
    }


    public function getOfferPaginated(QueryBuilder $queryBuilder, $page = 1): Paginator {
        $limit = 20;
        $firstResult = (abs($page) - 1) * $limit;

        // Add the first and max result limits
        $queryBuilder
            ->setFirstResult($firstResult)
            ->setMaxResults($limit);
        // Generate the Query
        $query = $queryBuilder->getQuery();

        // Generate the Paginator
        return new Paginator($query, true);
    }

    public function searchQueryBuilder($title): QueryBuilder {
        $title = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

        $query = $this->createQueryBuilder('o')
            ->andWhere('o.title like :title')
            ->setParameter('title', '%' . $title . '%')
            ->orderBy('o.title', 'ASC');

        return $query;
    }

//    /**
//     * @return Offer[] Returns an array of Offer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offer
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

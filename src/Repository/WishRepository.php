<?php

    namespace App\Repository;


    use App\Entity\Wish;
    use Doctrine\Persistence\ManagerRegistry;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

    /**
     * @extends ServiceEntityRepository<Wish>
     */
    class WishRepository extends ServiceEntityRepository
    {
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Wish::class);
        }

        //    /**
        //     * @return Wish[] Returns an array of Course objects
        //     */
        //    public function findByExampleField($value): array
        //    {
        //        return $this->createQueryBuilder('c')
        //            ->andWhere('c.exampleField = :val')
        //            ->setParameter('val', $value)
        //            ->orderBy('c.id', 'ASC')
        //            ->setMaxResults(10)
        //            ->getQuery()
        //            ->getResult()
        //        ;
        //    }

        //    public function findOneBySomeField($value): ?Course
        //    {
        //        return $this->createQueryBuilder('c')
        //            ->andWhere('c.exampleField = :val')
        //            ->setParameter('val', $value)
        //            ->getQuery()
        //            ->getOneOrNullResult()
        //        ;
        //    }

    }
    ?>
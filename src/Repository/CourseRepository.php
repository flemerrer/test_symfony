<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Course>
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }
    public function findLastCourses(int $minDuration = 2) : Paginator
    {
        /*
        //DQL
             $dql = "SELECT c from App\Entity\Course as c
                     WHERE c.duration > :duration
                     ORDER BY c.dateCreated DESC";
                 // No limit en DQL => Il faut le faire en post query
                // On ne peut pas injecter un EntityManager dans un repo
                $query = $this->getEntityManager()->createQuery($dql);
                $query = $query->setParameter("duration", $minDuration);*/

        //QueryBuilder

        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder
            # important à ajouter pour limiter les requêtes !
            ->addSelect('category')
            ->leftJoin('c.category', 'category')
            ->addSelect('trainers')
            ->leftJoin('c.trainers', 'trainers')
            ->andWhere("c.duration > :duration")
            ->setParameter("duration", $minDuration)
            ->addOrderBy("c.dateCreated", "DESC")
            ->addOrderBy("c.name", "ASC");
        $query = $queryBuilder->getQuery();
        $query->setMaxResults(5);
//        // Equivalent du offset
//        $query->setFirstResult(0);
        return new Paginator($query);
    }

    //    /**
    //     * @return Course[] Returns an array of Course objects
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

<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\Casting;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Casting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Casting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Casting[]    findAll()
 * @method Casting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CastingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Casting::class);
    }


    /**
     * Tous les castings d'un film donné
     * joins sur l'entité Person
     * 
     * SELECT * FROM `casting`
     * INNER JOIN `person` ON `casting`.`person_id` = `person`.`id`
     * WHERE `movie_id` = 7
     */
    public function findAllByMovieJoinedToPerson(Movie $movie)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            // SELECT c.*, p.*
            'SELECT c, p
            -- FROM casting AS c
            FROM App\Entity\Casting AS c
            -- INNER JOIN person ON c.person_id = person.id
            INNER JOIN c.person AS p
            -- WHERE movie_id = 7
            WHERE c.movie = :id
            ORDER BY c.creditOrder'
        )->setParameter('id', $movie);

        return $query->getResult();
    }

    // /**
    //  * @return Casting[] Returns an array of Casting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Casting
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

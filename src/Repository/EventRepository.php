<?php

namespace App\Repository;

use DateTime;
use App\Entity\Event;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }


    /**
     * @return A paginator object of upcoming Event objects
     */
    public function getUpcomingOrderedByAscStartsAtPaginator(int $page): Pagerfanta
    {
        return (new Pagerfanta(new DoctrineORMAdapter($this->getUpcomingOrderedByAscStartsAtQueryBuilder())))
                  ->setMaxPerPage(Event::NUM_ITEMS)
                  ->setCurrentPage($page);
        
    }

    private function getUpcomingOrderedByAscStartsAtQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('e')
                    ->andWhere('e.startsAt >= :now')
                    ->setParameter('now', new DateTime())
                    ->orderBy('e.startsAt', 'ASC');
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

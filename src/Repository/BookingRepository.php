<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\FoodTruck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 *
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
     */
    public function add(Booking $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     */
    public function remove(Booking $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Booking[] Returns an array of Booking objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findUpcomingByFoodTruck(FoodTruck $foodTruck): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.foodTruck = :foodTruck')
            ->andWhere('b.booking_date >= :today')
            ->setParameter('foodTruck', $foodTruck)
            ->setParameter('today', new \DateTime())
            ->orderBy('b.booking_date', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findOneByFoodTruckAndDate(FoodTruck $foodTruck, \DateTimeInterface $date): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.foodTruck = :foodTruck')
            ->andWhere('b.booking_date = :date')
            ->setParameter('foodTruck', $foodTruck)
            ->setParameter('booking_date', $date)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    public function countByDate(\DateTime $bookingDate)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.bookingDate = :booking_date')
            ->setParameter('booking_date', $bookingDate);

        return $qb->getQuery()->getSingleScalarResult();
    }

}

<?php
// src/Repository/FoodTruckRepository.php

namespace App\Repository;

use App\Entity\FoodTruck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FoodTruckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FoodTruck::class);
    }

    public function findAvailable(\DateTimeInterface $date): array
    {
        // The Hooly company has 7 locations available, except on Fridays where they only have 6 available.
        // Each food truck can only come once a week.
        $dayOfWeek = (int) $date->format('N');
        $maxTrucks = $dayOfWeek === 5 ? 6 : 7;
        $usedSpots = $this->createQueryBuilder('ft')
            ->leftJoin('ft.bookings', 'b')
            ->andWhere('b.date = :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
        $usedSpotsCount = count($usedSpots);
        if ($usedSpotsCount >= $maxTrucks) {
            return [];
        }
        $usedTrucks = array_map(function ($usedSpot) {
            return $usedSpot->getFoodTruck()->getId();
        }, $usedSpots);
        return $this->createQueryBuilder('ft')
            ->andWhere('ft.id NOT IN (:usedTrucks)')
            ->setParameter('usedTrucks', $usedTrucks)
            ->getQuery()
            ->getResult();
    }
}

<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\FoodTruck;
use App\Repository\BookingRepository;
use App\Repository\FoodTruckRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/api/bookings", name="api_bookings_create", methods={"POST"})
     * @throws Exception
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // get food truck id and date from request
        $jsonData = json_decode($request->getContent(), true);
        $foodTruckId = $jsonData['food_truck_id'];

        //$foodTruckId = $request->get('food_truck_id');
        $bookingDate = new \DateTime($jsonData['booking_date']);

        // check if date is in the past or today
        $today = new \DateTime();
        if ($bookingDate <= $today) {
            return $this->json(['error' => 'Cannot book for a past or today date.'], 400);
        }

        // check if food truck already booked for the date
        $bookingRepository = $entityManager->getRepository(Booking::class);
        $existingBooking = $bookingRepository->findOneBy(['foodTruck' => $foodTruckId, 'bookingDate' => $bookingDate]);

        if ($existingBooking) {
            return $this->json(['error' => 'Food truck already booked for the date.'], 400);
        }

        // check if food truck already booked this week
        $weekStart = (new \DateTime())->modify('Monday this week');
        $weekEnd = (new \DateTime())->modify('Sunday this week');
        $existingBooking = $bookingRepository->findOneBy(['foodTruck' => $foodTruckId, 'bookingDate' => [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')]]);
        if ($existingBooking) {
            return $this->json(['error' => 'Food truck already booked this week.'], 400);
        }

        // check if all spots are taken for the date
        $bookingsCount = $bookingRepository->countByDate($bookingDate);
        $spotsCount = $bookingDate->format('l') === 'Friday' ? 6 : 7;
        if ($bookingsCount >= $spotsCount) {
            return $this->json(['error' => 'All spots for the date are taken.'], 400);
        }

        // create booking
        $foodTruck = $entityManager->getRepository(FoodTruck::class)->find($foodTruckId);
        $booking = new Booking();
        $booking->setFoodTruck($foodTruck);
        $booking->setBookingDate($bookingDate);
        $entityManager->persist($booking);
        $entityManager->flush();

        return $this->json(['id' => $booking->getId(), 'food_truck_id' => $foodTruckId, 'bookingDate' => $bookingDate->format('Y-m-d')], 201);
    }

    /**
     * @Route("/api/bookings", name="api_bookings_list", methods={"GET"})
     */
    public function list(BookingRepository $bookingRepository): JsonResponse
    {
        $bookings = $bookingRepository->findAll();

        $data = [];

        foreach ($bookings as $booking) {
            $data[] = [
                'id' => $booking->getId(),
                'food_truck' => $booking->getFoodTruck()->getName(),
                'bookingDate' => $booking->getBookingDate()->format('Y-m-d'),
            ];
        }

        return $this->json($data);
    }

    /**
     * @Route("/api/foodtrucks", name="api_food_trucks_list", methods={"GET"})
     */
    public function foodTruckList(FoodTruckRepository $foodTruckRepository): JsonResponse
    {
        $foodTrucks = $foodTruckRepository->findAll();

        $data = [];

        foreach ($foodTrucks as $foodTruck) {
            $data[] = [
                'id' => $foodTruck->getId(),
                'name' => $foodTruck->getName(),
            ];
        }

        return $this->json($data);
    }
}

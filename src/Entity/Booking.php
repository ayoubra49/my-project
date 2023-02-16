<?php
// src/Entity/Booking.php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual("today")
     */
    private $bookingDate;

    /**
     * @ORM\ManyToOne(targetEntity="FoodTruck", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $foodTruck;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getBookingDate(): ?DateTime
    {
        return $this->bookingDate;
    }

    public function setBookingDate(DateTime $bookingDate): self
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    public function getFoodTruck(): ?FoodTruck
    {
        return $this->foodTruck;
    }

    public function setFoodTruck(?FoodTruck $foodTruck): self
    {
        $this->foodTruck = $foodTruck;

        return $this;
    }
}

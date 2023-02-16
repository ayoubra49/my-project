<?php

declare(strict_types=1);

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230214232209 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create Booking Table';
    }

    public function up(Schema $schema) : void
    {
        // Insertion des FoodTruck
        $this->addSql("INSERT INTO food_truck (name, location, days_available) VALUES ('Truck 1', 'Lyon', '{\"Lundi\",\"Mercredi\",\"Vendredi\"}')");
        $this->addSql("INSERT INTO food_truck (name, location, days_available) VALUES ('Truck 2', 'Paris', '{\"Mardi\",\"Jeudi\"}')");

        // Get FoodTruck Ids
        $foodTruck1Id = $this->connection->lastInsertId();
        $foodTruck2Id = $foodTruck1Id + 1;
        $this->addSql("INSERT INTO booking (booking_date, food_truck_id) VALUES ('2023-02-20 12:00:00', {$foodTruck1Id})");
        $this->addSql("INSERT INTO booking (booking_date, food_truck_id) VALUES ('2023-02-21 13:30:00', {$foodTruck2Id})");
        $this->addSql("INSERT INTO booking (booking_date, food_truck_id) VALUES ('2023-02-22 11:15:00', {$foodTruck1Id})");
    }

    public function down(Schema $schema) : void
    {

    }
}

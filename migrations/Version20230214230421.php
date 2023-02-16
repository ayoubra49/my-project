<?php

declare(strict_types=1);

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230214230421 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create Booking Table';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->createTable('booking');
        $table->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $table->addColumn('booking_date', 'datetime');
        $table->addColumn('location', 'string', ['length' => 255]);
        $table->addColumn('truck_id', 'integer');
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('booking');
    }
}

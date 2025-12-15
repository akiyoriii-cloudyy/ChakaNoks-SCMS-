<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReceivedStatusToDeliveries extends Migration
{
    public function up()
    {
        // Add 'received' status to deliveries enum
        $this->db->query("ALTER TABLE `deliveries` MODIFY COLUMN `status` ENUM('scheduled', 'in_transit', 'received', 'delivered', 'partial_delivery', 'cancelled', 'delayed') NOT NULL DEFAULT 'scheduled'");
    }

    public function down()
    {
        // Remove 'received' status from deliveries enum
        $this->db->query("ALTER TABLE `deliveries` MODIFY COLUMN `status` ENUM('scheduled', 'in_transit', 'delivered', 'partial_delivery', 'cancelled', 'delayed') NOT NULL DEFAULT 'scheduled'");
    }
}

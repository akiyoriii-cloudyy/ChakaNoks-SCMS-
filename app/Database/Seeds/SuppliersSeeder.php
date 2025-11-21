<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SuppliersSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now();

        $suppliers = [
            ['name' => 'Magnolia Chicken', 'contact_person' => 'Sales Department', 'email' => 'sales@magnolia.com.ph', 'phone' => '(02) 8123-4567', 'address' => 'Magnolia Avenue, Quezon City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => "Ana's Breeders Farms Inc", 'contact_person' => 'Farm Manager', 'email' => 'info@anasbreeders.com.ph', 'phone' => '(049) 501-2345', 'address' => 'Tagaytay, Cavite, Philippines', 'payment_terms' => 'COD', 'status' => 'active'],
            ['name' => 'Premium Feeds Corporation', 'contact_person' => 'Sales Officer', 'email' => 'sales@premiumfeeds.com.ph', 'phone' => '(02) 8456-7890', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            ['name' => 'San Miguel Foods', 'contact_person' => 'Corporate Sales', 'email' => 'corporate@sanmiguelfoods.com.ph', 'phone' => '(02) 8888-0000', 'address' => 'Mandaluyong City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'CDO Foodsphere Inc.', 'contact_person' => 'Sales Department', 'email' => 'sales@cdofoodsphere.com.ph', 'phone' => '(02) 8123-5678', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Excellence Poultry and Livestock Specialist Inc.', 'contact_person' => 'Operations Manager', 'email' => 'info@excellencepoultry.com.ph', 'phone' => '(049) 502-3456', 'address' => 'Cavite, Philippines', 'payment_terms' => 'COD', 'status' => 'active'],
            ['name' => 'Rare Global Food Trading Corp.', 'contact_person' => 'Trading Manager', 'email' => 'trading@rareglobal.com.ph', 'phone' => '(02) 8234-5678', 'address' => 'Pasig City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            ['name' => 'E&L Faster Food Imports Inc.', 'contact_person' => 'Import Manager', 'email' => 'imports@elfaster.com.ph', 'phone' => '(02) 8345-6789', 'address' => 'Port Area, Manila, Philippines', 'payment_terms' => 'Net 45', 'status' => 'active'],
            ['name' => 'Foster Foods Inc', 'contact_person' => 'Sales Coordinator', 'email' => 'sales@fosterfoods.com.ph', 'phone' => '(02) 8456-7890', 'address' => 'Taguig City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Pilmico', 'contact_person' => 'Corporate Sales', 'email' => 'corporate@pilmico.com.ph', 'phone' => '(02) 8567-8901', 'address' => 'Laguna, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Consistent Frozen Solutions Corp.', 'contact_person' => 'Sales Manager', 'email' => 'sales@consistentfrozen.com.ph', 'phone' => '(02) 8678-9012', 'address' => 'Cavite, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            ['name' => 'EcoSci Food', 'contact_person' => 'Business Development', 'email' => 'bd@ecoscifood.com.ph', 'phone' => '(02) 8789-0123', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'COD', 'status' => 'active'],
            ['name' => 'Advance Protein Inc.', 'contact_person' => 'Sales Officer', 'email' => 'sales@advanceprotein.com.ph', 'phone' => '(02) 8890-1234', 'address' => 'Bulacan, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Art Inc.', 'contact_person' => 'Account Manager', 'email' => 'accounts@artinc.com.ph', 'phone' => '(02) 8901-2345', 'address' => 'Manila, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            ['name' => 'Clarc Feedmill Inc.', 'contact_person' => 'Production Manager', 'email' => 'production@clarcfeedmill.com.ph', 'phone' => '(049) 503-4567', 'address' => 'Cavite, Philippines', 'payment_terms' => 'COD', 'status' => 'active'],
            ['name' => 'Kai Anya Foods Intl Corp', 'contact_person' => 'International Sales', 'email' => 'sales@kaianya.com.ph', 'phone' => '(02) 8012-3456', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Hightower Incorporated', 'contact_person' => 'Sales Department', 'email' => 'sales@hightower.com.ph', 'phone' => '(02) 8123-4567', 'address' => 'Pasig City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'The Original Savory Escolta - Online', 'contact_person' => 'Online Manager', 'email' => 'online@savoryescolta.com.ph', 'phone' => '(02) 8234-5678', 'address' => 'Escolta, Manila, Philippines', 'payment_terms' => 'COD', 'status' => 'active'],
            ['name' => 'Fresco PH', 'contact_person' => 'Sales Team', 'email' => 'sales@fresco.com.ph', 'phone' => '(02) 8345-6789', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
        ];

        $tbl = $this->db->table('suppliers');

        foreach ($suppliers as $supplier) {
            $existing = $tbl->where('name', $supplier['name'])->get()->getRowArray();

            if ($existing) {
                $tbl->where('id', $existing['id'])->update(array_merge($supplier, ['updated_at' => $now]));
                echo "✅ Updated supplier: {$supplier['name']}\n";
            } else {
                $tbl->insert(array_merge($supplier, ['created_at' => $now, 'updated_at' => $now]));
                echo "✅ Inserted supplier: {$supplier['name']}\n";
            }
        }

        echo "\n✅ Suppliers seeding completed!\n";
    }
}
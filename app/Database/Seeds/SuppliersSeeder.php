<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SuppliersSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now();

        // Comprehensive suppliers aligned with all 20 categories for a chicken restaurant
        $suppliers = [
            // ===== CHICKEN PARTS SUPPLIERS =====
            ['name' => 'Magnolia Chicken', 'contact_person' => 'Sales Department', 'email' => 'sales@magnolia.com.ph', 'phone' => '(02) 8123-4567', 'address' => 'Magnolia Avenue, Quezon City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => "Ana's Breeders Farms Inc", 'contact_person' => 'Farm Manager', 'email' => 'info@anasbreeders.com.ph', 'phone' => '(049) 501-2345', 'address' => 'Tagaytay, Cavite, Philippines', 'payment_terms' => 'COD', 'status' => 'active'],
            ['name' => 'Excellence Poultry and Livestock Specialist Inc.', 'contact_person' => 'Operations Manager', 'email' => 'info@excellencepoultry.com.ph', 'phone' => '(049) 502-3456', 'address' => 'Cavite, Philippines', 'payment_terms' => 'COD', 'status' => 'active'],
            ['name' => 'Rare Global Food Trading Corp.', 'contact_person' => 'Trading Manager', 'email' => 'trading@rareglobal.com.ph', 'phone' => '(02) 8234-5678', 'address' => 'Pasig City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            ['name' => 'Advance Protein Inc.', 'contact_person' => 'Sales Officer', 'email' => 'sales@advanceprotein.com.ph', 'phone' => '(02) 8890-1234', 'address' => 'Bulacan, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            
            // ===== BEVERAGES SUPPLIERS =====
            ['name' => 'Coca-Cola Beverages Philippines Inc.', 'contact_person' => 'Corporate Sales', 'email' => 'corporate@coca-cola.com.ph', 'phone' => '(02) 8888-1111', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Pepsi-Cola Products Philippines Inc.', 'contact_person' => 'Sales Manager', 'email' => 'sales@pepsi.com.ph', 'phone' => '(02) 8888-2222', 'address' => 'Mandaluyong City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Universal Robina Corporation (URC)', 'contact_person' => 'Beverage Division', 'email' => 'beverages@urc.com.ph', 'phone' => '(02) 8888-3333', 'address' => 'Pasig City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Nestlé Philippines Inc. (Beverages)', 'contact_person' => 'Beverage Sales', 'email' => 'beverages@nestle.com.ph', 'phone' => '(02) 8888-4444', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            
            // ===== CONDIMENTS & SAUCES SUPPLIERS =====
            ['name' => 'CDO Foodsphere Inc.', 'contact_person' => 'Sales Department', 'email' => 'sales@cdofoodsphere.com.ph', 'phone' => '(02) 8123-5678', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'San Miguel Foods', 'contact_person' => 'Corporate Sales', 'email' => 'corporate@sanmiguelfoods.com.ph', 'phone' => '(02) 8888-0000', 'address' => 'Mandaluyong City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Heinz Philippines Inc.', 'contact_person' => 'Sales Team', 'email' => 'sales@heinz.com.ph', 'phone' => '(02) 8888-5555', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Datu Puti Food Products', 'contact_person' => 'Sales Department', 'email' => 'sales@dataputi.com.ph', 'phone' => '(02) 8888-6666', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            
            // ===== COOKING OILS SUPPLIERS =====
            ['name' => 'Bunge Philippines Inc.', 'contact_person' => 'Oil Division', 'email' => 'oils@bunge.com.ph', 'phone' => '(02) 8888-7777', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Cargill Philippines Inc.', 'contact_person' => 'Cooking Oil Sales', 'email' => 'oils@cargill.com.ph', 'phone' => '(02) 8888-8888', 'address' => 'Taguig City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Purefoods-Hormel Company Inc.', 'contact_person' => 'Oil Products', 'email' => 'oils@purefoods.com.ph', 'phone' => '(02) 8888-9999', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            
            // ===== SEASONINGS & SPICES SUPPLIERS =====
            ['name' => 'McCormick Philippines Inc.', 'contact_person' => 'Sales Department', 'email' => 'sales@mccormick.com.ph', 'phone' => '(02) 8888-1010', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Knorr Philippines', 'contact_person' => 'Seasoning Sales', 'email' => 'sales@knorr.com.ph', 'phone' => '(02) 8888-2020', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Ajinomoto Philippines Corporation', 'contact_person' => 'Sales Team', 'email' => 'sales@ajinomoto.com.ph', 'phone' => '(02) 8888-3030', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            
            // ===== RICE & GRAINS SUPPLIERS =====
            ['name' => 'National Food Authority (NFA)', 'contact_person' => 'Rice Distribution', 'email' => 'distribution@nfa.gov.ph', 'phone' => '(02) 8888-4040', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            ['name' => 'Doña Maria Rice Trading', 'contact_person' => 'Sales Manager', 'email' => 'sales@donamaria.com.ph', 'phone' => '(02) 8888-5050', 'address' => 'Manila, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            ['name' => 'Golden Grains Trading Corp.', 'contact_person' => 'Trading Manager', 'email' => 'sales@goldengrains.com.ph', 'phone' => '(02) 8888-6060', 'address' => 'Pasig City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            
            // ===== VEGETABLES & PRODUCE SUPPLIERS =====
            ['name' => 'Fresco PH', 'contact_person' => 'Sales Team', 'email' => 'sales@fresco.com.ph', 'phone' => '(02) 8345-6789', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            ['name' => 'Farm Fresh Produce Trading', 'contact_person' => 'Sales Manager', 'email' => 'sales@farmfresh.com.ph', 'phone' => '(02) 8888-7070', 'address' => 'Laguna, Philippines', 'payment_terms' => 'COD', 'status' => 'active'],
            ['name' => 'Benguet Vegetable Trading', 'contact_person' => 'Trading Manager', 'email' => 'sales@benguetveg.com.ph', 'phone' => '(074) 444-1234', 'address' => 'Benguet, Philippines', 'payment_terms' => 'Net 7', 'status' => 'active'],
            
            // ===== BREAD & BAKERY SUPPLIERS =====
            ['name' => 'Gardenia Bakeries Philippines Inc.', 'contact_person' => 'Sales Department', 'email' => 'sales@gardenia.com.ph', 'phone' => '(02) 8888-8080', 'address' => 'Laguna, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Rebisco Biscuit Corporation', 'contact_person' => 'Bakery Sales', 'email' => 'bakery@rebisco.com.ph', 'phone' => '(02) 8888-9090', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Local Bakery Suppliers Cooperative', 'contact_person' => 'Coordinator', 'email' => 'info@localbakery.com.ph', 'phone' => '(02) 8888-0101', 'address' => 'Manila, Philippines', 'payment_terms' => 'COD', 'status' => 'active'],
            
            // ===== DAIRY PRODUCTS SUPPLIERS =====
            ['name' => 'Nestlé Philippines Inc. (Dairy)', 'contact_person' => 'Dairy Division', 'email' => 'dairy@nestle.com.ph', 'phone' => '(02) 8888-1111', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Magnolia Dairy Products', 'contact_person' => 'Sales Department', 'email' => 'dairy@magnolia.com.ph', 'phone' => '(02) 8888-1212', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Alaska Milk Corporation', 'contact_person' => 'Sales Team', 'email' => 'sales@alaska.com.ph', 'phone' => '(02) 8888-1313', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            
            // ===== FROZEN GOODS SUPPLIERS =====
            ['name' => 'Consistent Frozen Solutions Corp.', 'contact_person' => 'Sales Manager', 'email' => 'sales@consistentfrozen.com.ph', 'phone' => '(02) 8678-9012', 'address' => 'Cavite, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            ['name' => 'E&L Faster Food Imports Inc.', 'contact_person' => 'Import Manager', 'email' => 'imports@elfaster.com.ph', 'phone' => '(02) 8345-6789', 'address' => 'Port Area, Manila, Philippines', 'payment_terms' => 'Net 45', 'status' => 'active'],
            ['name' => 'Arctic Foods Distribution', 'contact_person' => 'Sales Manager', 'email' => 'sales@arcticfoods.com.ph', 'phone' => '(02) 8888-1414', 'address' => 'Pasig City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            
            // ===== PACKAGING & SUPPLIES SUPPLIERS =====
            ['name' => 'Packaging Solutions Philippines Inc.', 'contact_person' => 'Sales Department', 'email' => 'sales@packagingsolutions.com.ph', 'phone' => '(02) 8888-1515', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Eco-Pack Manufacturing Corp.', 'contact_person' => 'Sales Manager', 'email' => 'sales@ecopack.com.ph', 'phone' => '(02) 8888-1616', 'address' => 'Laguna, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Takeout Packaging Supplies', 'contact_person' => 'Sales Team', 'email' => 'sales@takeoutpack.com.ph', 'phone' => '(02) 8888-1717', 'address' => 'Manila, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            
            // ===== CLEANING & SANITATION SUPPLIERS =====
            ['name' => 'Procter & Gamble Philippines', 'contact_person' => 'Commercial Sales', 'email' => 'commercial@p&g.com.ph', 'phone' => '(02) 8888-1818', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Unilever Philippines Inc.', 'contact_person' => 'Commercial Division', 'email' => 'commercial@unilever.com.ph', 'phone' => '(02) 8888-1919', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Industrial Cleaning Supplies Corp.', 'contact_person' => 'Sales Manager', 'email' => 'sales@industrialcleaning.com.ph', 'phone' => '(02) 8888-2020', 'address' => 'Pasig City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            
            // ===== EGGS SUPPLIERS =====
            ['name' => 'Fresh Eggs Distribution Co.', 'contact_person' => 'Sales Manager', 'email' => 'sales@fresheggs.com.ph', 'phone' => '(02) 8888-2121', 'address' => 'Bulacan, Philippines', 'payment_terms' => 'COD', 'status' => 'active'],
            ['name' => 'Poultry Egg Suppliers Association', 'contact_person' => 'Coordinator', 'email' => 'info@poultryeggs.com.ph', 'phone' => '(02) 8888-2222', 'address' => 'Laguna, Philippines', 'payment_terms' => 'Net 7', 'status' => 'active'],
            
            // ===== FLOUR & BREADING SUPPLIERS =====
            ['name' => 'Pilmico', 'contact_person' => 'Corporate Sales', 'email' => 'corporate@pilmico.com.ph', 'phone' => '(02) 8567-8901', 'address' => 'Laguna, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'General Milling Corporation', 'contact_person' => 'Sales Department', 'email' => 'sales@gmc.com.ph', 'phone' => '(02) 8888-2323', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Bread Flour Specialists Inc.', 'contact_person' => 'Sales Manager', 'email' => 'sales@breadflour.com.ph', 'phone' => '(02) 8888-2424', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            
            // ===== MARINADES & BRINES SUPPLIERS =====
            ['name' => 'Flavor Masters Food Products', 'contact_person' => 'Sales Team', 'email' => 'sales@flavormasters.com.ph', 'phone' => '(02) 8888-2525', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Marinade Specialists Philippines', 'contact_person' => 'Sales Manager', 'email' => 'sales@marinadespecialists.com.ph', 'phone' => '(02) 8888-2626', 'address' => 'Pasig City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            
            // ===== SIDE DISHES SUPPLIERS =====
            ['name' => 'Ready-to-Serve Foods Inc.', 'contact_person' => 'Sales Department', 'email' => 'sales@readyfoods.com.ph', 'phone' => '(02) 8888-2727', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            ['name' => 'Prepared Foods Distribution', 'contact_person' => 'Sales Manager', 'email' => 'sales@preparedfoods.com.ph', 'phone' => '(02) 8888-2828', 'address' => 'Manila, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            
            // ===== DESSERTS & SWEETS SUPPLIERS =====
            ['name' => 'Sweet Treats Philippines Inc.', 'contact_person' => 'Sales Team', 'email' => 'sales@sweettreats.com.ph', 'phone' => '(02) 8888-2929', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Ice Cream & Dessert Suppliers', 'contact_person' => 'Sales Manager', 'email' => 'sales@icecreamdesserts.com.ph', 'phone' => '(02) 8888-3030', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            
            // ===== PAPER PRODUCTS SUPPLIERS =====
            ['name' => 'Paper Products Manufacturing Corp.', 'contact_person' => 'Sales Department', 'email' => 'sales@paperproducts.com.ph', 'phone' => '(02) 8888-3131', 'address' => 'Laguna, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Tissue & Paper Supplies Inc.', 'contact_person' => 'Sales Manager', 'email' => 'sales@tissuepaper.com.ph', 'phone' => '(02) 8888-3232', 'address' => 'Manila, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            
            // ===== KITCHEN EQUIPMENT SUPPLIERS =====
            ['name' => 'Commercial Kitchen Equipment Inc.', 'contact_person' => 'Sales Department', 'email' => 'sales@kitchenequipment.com.ph', 'phone' => '(02) 8888-3333', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Restaurant Supply Solutions', 'contact_person' => 'Sales Manager', 'email' => 'sales@restaurantsupply.com.ph', 'phone' => '(02) 8888-3434', 'address' => 'Pasig City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            
            // ===== UNIFORMS & APPAREL SUPPLIERS =====
            ['name' => 'Uniform Solutions Philippines', 'contact_person' => 'Sales Team', 'email' => 'sales@uniformsolutions.com.ph', 'phone' => '(02) 8888-3535', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Professional Apparel Manufacturing', 'contact_person' => 'Sales Manager', 'email' => 'sales@proapparel.com.ph', 'phone' => '(02) 8888-3636', 'address' => 'Manila, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            
            // ===== GENERAL/MULTI-CATEGORY SUPPLIERS =====
            ['name' => 'Premium Feeds Corporation', 'contact_person' => 'Sales Officer', 'email' => 'sales@premiumfeeds.com.ph', 'phone' => '(02) 8456-7890', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            ['name' => 'Foster Foods Inc', 'contact_person' => 'Sales Coordinator', 'email' => 'sales@fosterfoods.com.ph', 'phone' => '(02) 8456-7890', 'address' => 'Taguig City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'EcoSci Food', 'contact_person' => 'Business Development', 'email' => 'bd@ecoscifood.com.ph', 'phone' => '(02) 8789-0123', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'COD', 'status' => 'active'],
            ['name' => 'Art Inc.', 'contact_person' => 'Account Manager', 'email' => 'accounts@artinc.com.ph', 'phone' => '(02) 8901-2345', 'address' => 'Manila, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active'],
            ['name' => 'Clarc Feedmill Inc.', 'contact_person' => 'Production Manager', 'email' => 'production@clarcfeedmill.com.ph', 'phone' => '(049) 503-4567', 'address' => 'Cavite, Philippines', 'payment_terms' => 'COD', 'status' => 'active'],
            ['name' => 'Kai Anya Foods Intl Corp', 'contact_person' => 'International Sales', 'email' => 'sales@kaianya.com.ph', 'phone' => '(02) 8012-3456', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'Hightower Incorporated', 'contact_person' => 'Sales Department', 'email' => 'sales@hightower.com.ph', 'phone' => '(02) 8123-4567', 'address' => 'Pasig City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active'],
            ['name' => 'The Original Savory Escolta - Online', 'contact_person' => 'Online Manager', 'email' => 'online@savoryescolta.com.ph', 'phone' => '(02) 8234-5678', 'address' => 'Escolta, Manila, Philippines', 'payment_terms' => 'COD', 'status' => 'active'],
        ];

        $tbl = $this->db->table('suppliers');
        $inserted = 0;
        $updated = 0;

        foreach ($suppliers as $supplier) {
            // Check by name first, then by email if name doesn't exist
            $existing = $tbl->where('name', $supplier['name'])->get()->getRowArray();
            
            if (!$existing && !empty($supplier['email'])) {
                // Also check by email to avoid duplicates
                $existingByEmail = $tbl->where('email', $supplier['email'])->get()->getRowArray();
                if ($existingByEmail) {
                    echo "⏭️ Skipped (duplicate email): {$supplier['name']} - email {$supplier['email']} already exists\n";
                    continue;
                }
            }

            if ($existing) {
                $tbl->where('id', $existing['id'])->update(array_merge($supplier, ['updated_at' => $now]));
                $updated++;
                echo "🔄 Updated supplier: {$supplier['name']}\n";
            } else {
                $tbl->insert(array_merge($supplier, ['created_at' => $now, 'updated_at' => $now]));
                $inserted++;
                echo "✅ Inserted supplier: {$supplier['name']}\n";
            }
        }

        echo "\n📊 Summary: Inserted {$inserted} new suppliers, Updated {$updated} existing suppliers\n";
        echo "✅ Suppliers seeding completed! Total suppliers: " . ($inserted + $updated) . "\n";
    }
}

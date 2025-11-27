<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now();

        $branchRows = $this->db->table('branches')->select('id, code')->get()->getResultArray();
        $branchMap = [];
        foreach ($branchRows as $branchRow) {
            $branchMap[strtoupper($branchRow['code'])] = $branchRow['id'];
        }

        // Get all inventory staff users
        $invUsers = $this->db->table('users')
            ->where('role', 'inventory_staff')
            ->get()->getResultArray();

        // Use first inventory staff as default created_by
        $defaultUser = !empty($invUsers) ? (int) $invUsers[0]['id'] : null;

        // Get all categories
        $categories = $this->db->table('categories')
            ->where('status', 'active')
            ->get()->getResultArray();
        
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['name']] = $cat['id'];
        }

        // Define products for each category - comprehensive chicken restaurant inventory
        $productsByCategory = [
            'Chicken Parts' => [
                ['name' => 'Whole Chicken', 'stock_qty' => 500, 'unit' => 'kg', 'price' => 250.00, 'min_stock' => 100, 'max_stock' => 1000, 'expiry_days' => 105],
                ['name' => 'Chicken Breast', 'stock_qty' => 600, 'unit' => 'kg', 'price' => 280.00, 'min_stock' => 100, 'max_stock' => 1000, 'expiry_days' => 93],
                ['name' => 'Chicken Thigh', 'stock_qty' => 550, 'unit' => 'kg', 'price' => 220.00, 'min_stock' => 100, 'max_stock' => 1000, 'expiry_days' => 90],
                ['name' => 'Chicken Wings', 'stock_qty' => 450, 'unit' => 'kg', 'price' => 180.00, 'min_stock' => 100, 'max_stock' => 800, 'expiry_days' => 85],
                ['name' => 'Chicken Drumstick', 'stock_qty' => 500, 'unit' => 'kg', 'price' => 200.00, 'min_stock' => 100, 'max_stock' => 900, 'expiry_days' => 90],
                ['name' => 'Chicken Liver', 'stock_qty' => 300, 'unit' => 'kg', 'price' => 120.00, 'min_stock' => 50, 'max_stock' => 600, 'expiry_days' => 87],
                ['name' => 'Chicken Gizzard', 'stock_qty' => 280, 'unit' => 'kg', 'price' => 130.00, 'min_stock' => 50, 'max_stock' => 600, 'expiry_days' => 88],
                ['name' => 'Chicken Feet', 'stock_qty' => 200, 'unit' => 'kg', 'price' => 90.00, 'min_stock' => 50, 'max_stock' => 500, 'expiry_days' => 92],
                ['name' => 'Ground Chicken', 'stock_qty' => 150, 'unit' => 'kg', 'price' => 240.00, 'min_stock' => 30, 'max_stock' => 400, 'expiry_days' => 80],
                ['name' => 'Chicken Leg Quarter', 'stock_qty' => 350, 'unit' => 'kg', 'price' => 210.00, 'min_stock' => 80, 'max_stock' => 700, 'expiry_days' => 89],
            ],
            'Beverages' => [
                ['name' => 'Coca-Cola (1.5L)', 'stock_qty' => 200, 'unit' => 'bottles', 'price' => 65.00, 'min_stock' => 50, 'max_stock' => 500, 'expiry_days' => 365],
                ['name' => 'Sprite (1.5L)', 'stock_qty' => 180, 'unit' => 'bottles', 'price' => 65.00, 'min_stock' => 50, 'max_stock' => 500, 'expiry_days' => 365],
                ['name' => 'Royal (1.5L)', 'stock_qty' => 150, 'unit' => 'bottles', 'price' => 65.00, 'min_stock' => 50, 'max_stock' => 500, 'expiry_days' => 365],
                ['name' => 'Bottled Water (500ml)', 'stock_qty' => 500, 'unit' => 'bottles', 'price' => 15.00, 'min_stock' => 100, 'max_stock' => 1000, 'expiry_days' => 730],
                ['name' => 'Iced Tea Powder', 'stock_qty' => 50, 'unit' => 'packs', 'price' => 350.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => 365],
                ['name' => 'Orange Juice', 'stock_qty' => 100, 'unit' => 'bottles', 'price' => 85.00, 'min_stock' => 30, 'max_stock' => 300, 'expiry_days' => 180],
                ['name' => 'Coffee (3-in-1)', 'stock_qty' => 200, 'unit' => 'packs', 'price' => 8.00, 'min_stock' => 50, 'max_stock' => 500, 'expiry_days' => 365],
            ],
            'Condiments & Sauces' => [
                ['name' => 'Ketchup (Gallon)', 'stock_qty' => 30, 'unit' => 'bottles', 'price' => 450.00, 'min_stock' => 10, 'max_stock' => 100, 'expiry_days' => 365],
                ['name' => 'Mayonnaise (Gallon)', 'stock_qty' => 25, 'unit' => 'bottles', 'price' => 520.00, 'min_stock' => 10, 'max_stock' => 80, 'expiry_days' => 180],
                ['name' => 'Hot Sauce', 'stock_qty' => 50, 'unit' => 'bottles', 'price' => 85.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => 365],
                ['name' => 'Soy Sauce (Gallon)', 'stock_qty' => 20, 'unit' => 'bottles', 'price' => 280.00, 'min_stock' => 10, 'max_stock' => 60, 'expiry_days' => 730],
                ['name' => 'Gravy Mix', 'stock_qty' => 100, 'unit' => 'packs', 'price' => 45.00, 'min_stock' => 30, 'max_stock' => 300, 'expiry_days' => 365],
                ['name' => 'BBQ Sauce', 'stock_qty' => 40, 'unit' => 'bottles', 'price' => 180.00, 'min_stock' => 15, 'max_stock' => 100, 'expiry_days' => 365],
                ['name' => 'Sweet Chili Sauce', 'stock_qty' => 35, 'unit' => 'bottles', 'price' => 150.00, 'min_stock' => 15, 'max_stock' => 100, 'expiry_days' => 365],
            ],
            'Cooking Oils' => [
                ['name' => 'Vegetable Oil (20L)', 'stock_qty' => 15, 'unit' => 'pcs', 'price' => 1850.00, 'min_stock' => 5, 'max_stock' => 50, 'expiry_days' => 365],
                ['name' => 'Vegetable Oil (5L)', 'stock_qty' => 30, 'unit' => 'pcs', 'price' => 480.00, 'min_stock' => 10, 'max_stock' => 100, 'expiry_days' => 365],
                ['name' => 'Palm Oil (20L)', 'stock_qty' => 10, 'unit' => 'pcs', 'price' => 1650.00, 'min_stock' => 5, 'max_stock' => 40, 'expiry_days' => 365],
                ['name' => 'Shortening', 'stock_qty' => 20, 'unit' => 'kg', 'price' => 120.00, 'min_stock' => 10, 'max_stock' => 80, 'expiry_days' => 365],
            ],
            'Seasonings & Spices' => [
                ['name' => 'Salt (1kg)', 'stock_qty' => 100, 'unit' => 'packs', 'price' => 25.00, 'min_stock' => 30, 'max_stock' => 300, 'expiry_days' => 1095],
                ['name' => 'Black Pepper (Ground)', 'stock_qty' => 50, 'unit' => 'packs', 'price' => 85.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => 730],
                ['name' => 'Garlic Powder', 'stock_qty' => 40, 'unit' => 'packs', 'price' => 95.00, 'min_stock' => 15, 'max_stock' => 120, 'expiry_days' => 730],
                ['name' => 'Paprika', 'stock_qty' => 30, 'unit' => 'packs', 'price' => 120.00, 'min_stock' => 10, 'max_stock' => 100, 'expiry_days' => 730],
                ['name' => 'Chicken Seasoning', 'stock_qty' => 60, 'unit' => 'packs', 'price' => 75.00, 'min_stock' => 20, 'max_stock' => 200, 'expiry_days' => 365],
                ['name' => 'MSG', 'stock_qty' => 50, 'unit' => 'packs', 'price' => 45.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => 1095],
                ['name' => 'Cajun Seasoning', 'stock_qty' => 25, 'unit' => 'packs', 'price' => 150.00, 'min_stock' => 10, 'max_stock' => 80, 'expiry_days' => 730],
            ],
            'Rice & Grains' => [
                ['name' => 'White Rice (25kg)', 'stock_qty' => 50, 'unit' => 'bags', 'price' => 1450.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => 365],
                ['name' => 'White Rice (10kg)', 'stock_qty' => 30, 'unit' => 'bags', 'price' => 580.00, 'min_stock' => 15, 'max_stock' => 100, 'expiry_days' => 365],
                ['name' => 'Java Rice Mix', 'stock_qty' => 40, 'unit' => 'packs', 'price' => 65.00, 'min_stock' => 15, 'max_stock' => 120, 'expiry_days' => 365],
                ['name' => 'Garlic Rice Mix', 'stock_qty' => 45, 'unit' => 'packs', 'price' => 55.00, 'min_stock' => 15, 'max_stock' => 150, 'expiry_days' => 365],
            ],
            'Vegetables & Produce' => [
                ['name' => 'Cabbage', 'stock_qty' => 50, 'unit' => 'kg', 'price' => 45.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => 14],
                ['name' => 'Carrots', 'stock_qty' => 30, 'unit' => 'kg', 'price' => 60.00, 'min_stock' => 15, 'max_stock' => 100, 'expiry_days' => 21],
                ['name' => 'Onions (White)', 'stock_qty' => 40, 'unit' => 'kg', 'price' => 80.00, 'min_stock' => 15, 'max_stock' => 120, 'expiry_days' => 30],
                ['name' => 'Garlic (Bulb)', 'stock_qty' => 25, 'unit' => 'kg', 'price' => 180.00, 'min_stock' => 10, 'max_stock' => 80, 'expiry_days' => 60],
                ['name' => 'Potatoes', 'stock_qty' => 60, 'unit' => 'kg', 'price' => 55.00, 'min_stock' => 20, 'max_stock' => 200, 'expiry_days' => 30],
                ['name' => 'Lettuce', 'stock_qty' => 20, 'unit' => 'kg', 'price' => 120.00, 'min_stock' => 10, 'max_stock' => 60, 'expiry_days' => 7],
                ['name' => 'Coleslaw Mix', 'stock_qty' => 30, 'unit' => 'kg', 'price' => 95.00, 'min_stock' => 15, 'max_stock' => 100, 'expiry_days' => 7],
            ],
            'Bread & Bakery' => [
                ['name' => 'Burger Buns', 'stock_qty' => 200, 'unit' => 'pcs', 'price' => 15.00, 'min_stock' => 50, 'max_stock' => 500, 'expiry_days' => 7],
                ['name' => 'Sandwich Bread', 'stock_qty' => 50, 'unit' => 'packs', 'price' => 65.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => 7],
                ['name' => 'Pandesal', 'stock_qty' => 300, 'unit' => 'pcs', 'price' => 5.00, 'min_stock' => 100, 'max_stock' => 600, 'expiry_days' => 3],
                ['name' => 'Tortilla Wraps', 'stock_qty' => 100, 'unit' => 'pcs', 'price' => 12.00, 'min_stock' => 30, 'max_stock' => 300, 'expiry_days' => 14],
            ],
            'Dairy Products' => [
                ['name' => 'Butter (Salted)', 'stock_qty' => 30, 'unit' => 'pcs', 'price' => 180.00, 'min_stock' => 10, 'max_stock' => 100, 'expiry_days' => 90],
                ['name' => 'Cheese (Cheddar Block)', 'stock_qty' => 20, 'unit' => 'kg', 'price' => 450.00, 'min_stock' => 10, 'max_stock' => 60, 'expiry_days' => 90],
                ['name' => 'Cheese (Sliced)', 'stock_qty' => 50, 'unit' => 'packs', 'price' => 85.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => 60],
                ['name' => 'Milk (Fresh 1L)', 'stock_qty' => 40, 'unit' => 'bottles', 'price' => 95.00, 'min_stock' => 20, 'max_stock' => 120, 'expiry_days' => 14],
                ['name' => 'Heavy Cream', 'stock_qty' => 25, 'unit' => 'bottles', 'price' => 180.00, 'min_stock' => 10, 'max_stock' => 80, 'expiry_days' => 21],
            ],
            'Frozen Goods' => [
                ['name' => 'Frozen Fries (Regular)', 'stock_qty' => 100, 'unit' => 'packs', 'price' => 250.00, 'min_stock' => 30, 'max_stock' => 300, 'expiry_days' => 365],
                ['name' => 'Frozen Fries (Curly)', 'stock_qty' => 80, 'unit' => 'packs', 'price' => 280.00, 'min_stock' => 25, 'max_stock' => 250, 'expiry_days' => 365],
                ['name' => 'Frozen Fries (Wedges)', 'stock_qty' => 60, 'unit' => 'packs', 'price' => 290.00, 'min_stock' => 20, 'max_stock' => 200, 'expiry_days' => 365],
                ['name' => 'Frozen Corn', 'stock_qty' => 50, 'unit' => 'packs', 'price' => 120.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => 365],
                ['name' => 'Ice Cream (Vanilla)', 'stock_qty' => 30, 'unit' => 'pcs', 'price' => 350.00, 'min_stock' => 15, 'max_stock' => 100, 'expiry_days' => 180],
            ],
            'Packaging & Supplies' => [
                ['name' => 'Takeout Box (Small)', 'stock_qty' => 500, 'unit' => 'pcs', 'price' => 8.00, 'min_stock' => 200, 'max_stock' => 2000, 'expiry_days' => null],
                ['name' => 'Takeout Box (Medium)', 'stock_qty' => 400, 'unit' => 'pcs', 'price' => 12.00, 'min_stock' => 150, 'max_stock' => 1500, 'expiry_days' => null],
                ['name' => 'Takeout Box (Large)', 'stock_qty' => 300, 'unit' => 'pcs', 'price' => 18.00, 'min_stock' => 100, 'max_stock' => 1000, 'expiry_days' => null],
                ['name' => 'Paper Bag (Small)', 'stock_qty' => 600, 'unit' => 'pcs', 'price' => 3.00, 'min_stock' => 200, 'max_stock' => 2000, 'expiry_days' => null],
                ['name' => 'Paper Bag (Large)', 'stock_qty' => 400, 'unit' => 'pcs', 'price' => 5.00, 'min_stock' => 150, 'max_stock' => 1500, 'expiry_days' => null],
                ['name' => 'Plastic Utensils Set', 'stock_qty' => 1000, 'unit' => 'pcs', 'price' => 5.00, 'min_stock' => 300, 'max_stock' => 3000, 'expiry_days' => null],
                ['name' => 'Drinking Straws', 'stock_qty' => 2000, 'unit' => 'pcs', 'price' => 0.50, 'min_stock' => 500, 'max_stock' => 5000, 'expiry_days' => null],
                ['name' => 'Cup (12oz)', 'stock_qty' => 500, 'unit' => 'pcs', 'price' => 4.00, 'min_stock' => 200, 'max_stock' => 2000, 'expiry_days' => null],
                ['name' => 'Aluminum Foil', 'stock_qty' => 30, 'unit' => 'rolls', 'price' => 250.00, 'min_stock' => 10, 'max_stock' => 100, 'expiry_days' => null],
            ],
            'Cleaning & Sanitation' => [
                ['name' => 'Dish Soap (Gallon)', 'stock_qty' => 20, 'unit' => 'bottles', 'price' => 350.00, 'min_stock' => 10, 'max_stock' => 60, 'expiry_days' => 730],
                ['name' => 'Hand Sanitizer', 'stock_qty' => 50, 'unit' => 'bottles', 'price' => 150.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => 730],
                ['name' => 'Bleach', 'stock_qty' => 15, 'unit' => 'bottles', 'price' => 180.00, 'min_stock' => 5, 'max_stock' => 50, 'expiry_days' => 365],
                ['name' => 'Degreaser', 'stock_qty' => 20, 'unit' => 'bottles', 'price' => 280.00, 'min_stock' => 10, 'max_stock' => 60, 'expiry_days' => 730],
                ['name' => 'Trash Bags (Large)', 'stock_qty' => 100, 'unit' => 'packs', 'price' => 85.00, 'min_stock' => 30, 'max_stock' => 300, 'expiry_days' => null],
                ['name' => 'Disposable Gloves', 'stock_qty' => 50, 'unit' => 'boxes', 'price' => 250.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => null],
            ],
            'Eggs' => [
                ['name' => 'Eggs (Tray - 30pcs)', 'stock_qty' => 100, 'unit' => 'pcs', 'price' => 220.00, 'min_stock' => 30, 'max_stock' => 300, 'expiry_days' => 30],
                ['name' => 'Eggs (Dozen)', 'stock_qty' => 50, 'unit' => 'pcs', 'price' => 95.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => 30],
            ],
            'Flour & Breading' => [
                ['name' => 'All-Purpose Flour (25kg)', 'stock_qty' => 20, 'unit' => 'bags', 'price' => 950.00, 'min_stock' => 10, 'max_stock' => 60, 'expiry_days' => 365],
                ['name' => 'All-Purpose Flour (1kg)', 'stock_qty' => 50, 'unit' => 'packs', 'price' => 55.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => 365],
                ['name' => 'Breading Mix', 'stock_qty' => 80, 'unit' => 'packs', 'price' => 120.00, 'min_stock' => 30, 'max_stock' => 250, 'expiry_days' => 365],
                ['name' => 'Cornstarch', 'stock_qty' => 40, 'unit' => 'packs', 'price' => 45.00, 'min_stock' => 15, 'max_stock' => 120, 'expiry_days' => 730],
                ['name' => 'Panko Breadcrumbs', 'stock_qty' => 30, 'unit' => 'packs', 'price' => 150.00, 'min_stock' => 10, 'max_stock' => 100, 'expiry_days' => 365],
            ],
            'Marinades & Brines' => [
                ['name' => 'Chicken Marinade', 'stock_qty' => 40, 'unit' => 'bottles', 'price' => 180.00, 'min_stock' => 15, 'max_stock' => 120, 'expiry_days' => 365],
                ['name' => 'BBQ Marinade', 'stock_qty' => 30, 'unit' => 'bottles', 'price' => 195.00, 'min_stock' => 10, 'max_stock' => 100, 'expiry_days' => 365],
                ['name' => 'Buttermilk Brine', 'stock_qty' => 25, 'unit' => 'bottles', 'price' => 220.00, 'min_stock' => 10, 'max_stock' => 80, 'expiry_days' => 90],
                ['name' => 'Soy Garlic Marinade', 'stock_qty' => 35, 'unit' => 'bottles', 'price' => 165.00, 'min_stock' => 15, 'max_stock' => 100, 'expiry_days' => 365],
            ],
            'Side Dishes' => [
                ['name' => 'Coleslaw (Pre-made)', 'stock_qty' => 30, 'unit' => 'kg', 'price' => 150.00, 'min_stock' => 15, 'max_stock' => 100, 'expiry_days' => 7],
                ['name' => 'Mashed Potato Mix', 'stock_qty' => 50, 'unit' => 'packs', 'price' => 85.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => 365],
                ['name' => 'Gravy (Pre-made)', 'stock_qty' => 40, 'unit' => 'bottles', 'price' => 120.00, 'min_stock' => 15, 'max_stock' => 120, 'expiry_days' => 90],
                ['name' => 'Mac and Cheese Mix', 'stock_qty' => 30, 'unit' => 'packs', 'price' => 95.00, 'min_stock' => 15, 'max_stock' => 100, 'expiry_days' => 365],
            ],
            'Desserts & Sweets' => [
                ['name' => 'Chocolate Syrup', 'stock_qty' => 25, 'unit' => 'bottles', 'price' => 180.00, 'min_stock' => 10, 'max_stock' => 80, 'expiry_days' => 365],
                ['name' => 'Caramel Syrup', 'stock_qty' => 20, 'unit' => 'bottles', 'price' => 195.00, 'min_stock' => 10, 'max_stock' => 60, 'expiry_days' => 365],
                ['name' => 'Sundae Toppings', 'stock_qty' => 30, 'unit' => 'packs', 'price' => 85.00, 'min_stock' => 15, 'max_stock' => 100, 'expiry_days' => 180],
                ['name' => 'Brownie Mix', 'stock_qty' => 25, 'unit' => 'packs', 'price' => 120.00, 'min_stock' => 10, 'max_stock' => 80, 'expiry_days' => 365],
            ],
            'Paper Products' => [
                ['name' => 'Napkins (Pack)', 'stock_qty' => 200, 'unit' => 'packs', 'price' => 45.00, 'min_stock' => 50, 'max_stock' => 500, 'expiry_days' => null],
                ['name' => 'Paper Towels (Roll)', 'stock_qty' => 100, 'unit' => 'rolls', 'price' => 65.00, 'min_stock' => 30, 'max_stock' => 300, 'expiry_days' => null],
                ['name' => 'Tissue Paper (Box)', 'stock_qty' => 80, 'unit' => 'boxes', 'price' => 85.00, 'min_stock' => 30, 'max_stock' => 250, 'expiry_days' => null],
                ['name' => 'Parchment Paper', 'stock_qty' => 30, 'unit' => 'rolls', 'price' => 180.00, 'min_stock' => 10, 'max_stock' => 100, 'expiry_days' => null],
            ],
            'Kitchen Equipment' => [
                ['name' => 'Tongs', 'stock_qty' => 20, 'unit' => 'pcs', 'price' => 150.00, 'min_stock' => 5, 'max_stock' => 50, 'expiry_days' => null],
                ['name' => 'Spatula', 'stock_qty' => 15, 'unit' => 'pcs', 'price' => 120.00, 'min_stock' => 5, 'max_stock' => 40, 'expiry_days' => null],
                ['name' => 'Thermometer', 'stock_qty' => 10, 'unit' => 'pcs', 'price' => 350.00, 'min_stock' => 3, 'max_stock' => 30, 'expiry_days' => null],
                ['name' => 'Cutting Board', 'stock_qty' => 15, 'unit' => 'pcs', 'price' => 280.00, 'min_stock' => 5, 'max_stock' => 40, 'expiry_days' => null],
                ['name' => 'Strainer', 'stock_qty' => 10, 'unit' => 'pcs', 'price' => 180.00, 'min_stock' => 3, 'max_stock' => 30, 'expiry_days' => null],
            ],
            'Uniforms & Apparel' => [
                ['name' => 'Chef Hat', 'stock_qty' => 30, 'unit' => 'pcs', 'price' => 85.00, 'min_stock' => 10, 'max_stock' => 80, 'expiry_days' => null],
                ['name' => 'Hair Net', 'stock_qty' => 200, 'unit' => 'pcs', 'price' => 5.00, 'min_stock' => 50, 'max_stock' => 500, 'expiry_days' => null],
                ['name' => 'Apron', 'stock_qty' => 40, 'unit' => 'pcs', 'price' => 180.00, 'min_stock' => 15, 'max_stock' => 100, 'expiry_days' => null],
                ['name' => 'Kitchen Gloves', 'stock_qty' => 50, 'unit' => 'pcs', 'price' => 95.00, 'min_stock' => 20, 'max_stock' => 150, 'expiry_days' => null],
                ['name' => 'Face Mask', 'stock_qty' => 100, 'unit' => 'boxes', 'price' => 150.00, 'min_stock' => 30, 'max_stock' => 300, 'expiry_days' => null],
            ],
        ];

        // Branch codes to distribute products
        $branchCodes = array_keys($branchMap);
        if (empty($branchCodes)) {
            $branchCodes = ['MATINA', 'TORIL', 'BUHANGIN', 'AGDAO', 'LANANG'];
        }

        $tbl = $this->db->table('products');
        $insertCount = 0;
        $updateCount = 0;

        foreach ($productsByCategory as $categoryName => $products) {
            $categoryId = $categoryMap[$categoryName] ?? null;
            
            if (!$categoryId) {
                echo "⚠️ Category not found: {$categoryName}\n";
                continue;
            }

            foreach ($products as $index => $p) {
                // Distribute products across branches
                $branchCode = $branchCodes[$index % count($branchCodes)];
                $branchId = $branchMap[$branchCode] ?? null;

                $expiry = null;
                if (!empty($p['expiry_days'])) {
                    $expiry = date('Y-m-d', strtotime('+' . $p['expiry_days'] . ' days'));
                }

            $row = [
                'name'           => $p['name'],
                'category_id'    => $categoryId,
                'branch_id'      => $branchId,
                'created_by'     => $defaultUser,
                'stock_qty'      => $p['stock_qty'],
                'unit'           => $p['unit'],
                'price'          => $p['price'] ?? 0,
                'min_stock'      => $p['min_stock'],
                'max_stock'      => $p['max_stock'],
                    'expiry'         => $expiry,
                'status'         => 'active',
                'created_at'     => $now,
                'updated_at'     => $now,
            ];

                // Upsert (check by name + branch_id)
            $existing = $tbl->where('name', $row['name'])
                            ->where('branch_id', $row['branch_id'])
                            ->get()->getRowArray();

            if ($existing) {
                $tbl->where('id', $existing['id'])->update($row);
                    $updateCount++;
            } else {
                $tbl->insert($row);
                    $insertCount++;
            }
        }
            
            echo "✅ Processed category: {$categoryName}\n";
        }

        echo "\n📊 Summary: Inserted {$insertCount} new products, Updated {$updateCount} existing products\n";
    }
}

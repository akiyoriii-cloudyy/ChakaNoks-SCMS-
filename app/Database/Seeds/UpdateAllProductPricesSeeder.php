<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

/**
 * This seeder ensures ALL products from categoryItems.js have unit prices
 * It creates missing products and updates existing ones with prices
 */
class UpdateAllProductPricesSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now();
        
        // Get branches
        $branchRows = $this->db->table('branches')->select('id, code')->get()->getResultArray();
        $branchMap = [];
        foreach ($branchRows as $branchRow) {
            $branchMap[strtoupper($branchRow['code'])] = $branchRow['id'];
        }
        // Use a random branch for distribution
        $branchIds = array_values($branchMap);
        $defaultBranchId = !empty($branchIds) ? $branchIds[0] : null;

        // Get inventory staff users
        $invUsers = $this->db->table('users')
            ->where('role', 'inventory_staff')
            ->get()->getResultArray();
        $defaultUser = !empty($invUsers) ? (int) $invUsers[0]['id'] : null;

        // Get all categories
        $categories = $this->db->table('categories')
            ->where('status', 'active')
            ->get()->getResultArray();
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['name']] = $cat['id'];
        }

        // Comprehensive product list with prices - matching categoryItems.js
        $productsByCategory = [
            'Chicken Parts' => [
                ['name' => 'Whole Chicken', 'price' => 250.00, 'unit' => 'kg'],
                ['name' => 'Chicken Breast', 'price' => 280.00, 'unit' => 'kg'],
                ['name' => 'Chicken Thigh', 'price' => 220.00, 'unit' => 'kg'],
                ['name' => 'Chicken Wings', 'price' => 180.00, 'unit' => 'kg'],
                ['name' => 'Chicken Drumstick', 'price' => 200.00, 'unit' => 'kg'],
                ['name' => 'Chicken Liver', 'price' => 120.00, 'unit' => 'kg'],
                ['name' => 'Chicken Gizzard', 'price' => 130.00, 'unit' => 'kg'],
                ['name' => 'Chicken Feet', 'price' => 90.00, 'unit' => 'kg'],
                ['name' => 'Chicken Head', 'price' => 50.00, 'unit' => 'kg'],
                ['name' => 'Chicken Neck', 'price' => 90.00, 'unit' => 'kg'],
                ['name' => 'Chicken Back', 'price' => 80.00, 'unit' => 'kg'],
                ['name' => 'Chicken Heart', 'price' => 140.00, 'unit' => 'kg'],
                ['name' => 'Chicken Kidney', 'price' => 110.00, 'unit' => 'kg'],
                ['name' => 'Chicken Intestine', 'price' => 100.00, 'unit' => 'kg'],
                ['name' => 'Chicken Blood', 'price' => 60.00, 'unit' => 'kg'],
                ['name' => 'Chicken Skin', 'price' => 70.00, 'unit' => 'kg'],
                ['name' => 'Chicken Fat', 'price' => 65.00, 'unit' => 'kg'],
                ['name' => 'Chicken Bones', 'price' => 40.00, 'unit' => 'kg'],
                ['name' => 'Chicken Tail', 'price' => 75.00, 'unit' => 'kg'],
                ['name' => 'Chicken Leg Quarter', 'price' => 210.00, 'unit' => 'kg'],
                ['name' => 'Chicken Breast Fillet', 'price' => 300.00, 'unit' => 'kg'],
                ['name' => 'Chicken Tenderloin', 'price' => 320.00, 'unit' => 'kg'],
                ['name' => 'Chicken Wing Tip', 'price' => 85.00, 'unit' => 'kg'],
                ['name' => 'Chicken Wing Flat', 'price' => 190.00, 'unit' => 'kg'],
                ['name' => 'Chicken Wing Drumlette', 'price' => 195.00, 'unit' => 'kg'],
                ['name' => 'Ground Chicken', 'price' => 240.00, 'unit' => 'kg'],
                ['name' => 'Chicken Cutlet', 'price' => 260.00, 'unit' => 'kg'],
            ],
            'Beverages' => [
                ['name' => 'Coca-Cola (1.5L)', 'price' => 65.00, 'unit' => 'bottles'],
                ['name' => 'Coca-Cola (330ml can)', 'price' => 25.00, 'unit' => 'cans'],
                ['name' => 'Sprite (1.5L)', 'price' => 65.00, 'unit' => 'bottles'],
                ['name' => 'Sprite (330ml can)', 'price' => 25.00, 'unit' => 'cans'],
                ['name' => 'Royal (1.5L)', 'price' => 65.00, 'unit' => 'bottles'],
                ['name' => 'Royal (330ml can)', 'price' => 25.00, 'unit' => 'cans'],
                ['name' => 'Pepsi (1.5L)', 'price' => 65.00, 'unit' => 'bottles'],
                ['name' => 'Mountain Dew (1.5L)', 'price' => 65.00, 'unit' => 'bottles'],
                ['name' => 'Bottled Water (500ml)', 'price' => 15.00, 'unit' => 'bottles'],
                ['name' => 'Bottled Water (1L)', 'price' => 25.00, 'unit' => 'bottles'],
                ['name' => 'Mineral Water (6L)', 'price' => 85.00, 'unit' => 'bottles'],
                ['name' => 'Iced Tea Powder', 'price' => 350.00, 'unit' => 'packs'],
                ['name' => 'Orange Juice', 'price' => 85.00, 'unit' => 'bottles'],
                ['name' => 'Pineapple Juice', 'price' => 85.00, 'unit' => 'bottles'],
                ['name' => 'Mango Juice', 'price' => 85.00, 'unit' => 'bottles'],
                ['name' => 'Coffee (3-in-1)', 'price' => 8.00, 'unit' => 'packs'],
                ['name' => 'Hot Chocolate Mix', 'price' => 120.00, 'unit' => 'packs'],
                ['name' => 'Lemonade Mix', 'price' => 95.00, 'unit' => 'packs'],
                ['name' => 'Fruit Punch', 'price' => 90.00, 'unit' => 'bottles'],
                ['name' => 'Energy Drink', 'price' => 45.00, 'unit' => 'cans'],
                ['name' => 'Yakult', 'price' => 12.00, 'unit' => 'bottles'],
                ['name' => 'Fresh Milk (1L)', 'price' => 95.00, 'unit' => 'bottles'],
                ['name' => 'Chocolate Milk', 'price' => 100.00, 'unit' => 'bottles'],
            ],
            'Condiments & Sauces' => [
                ['name' => 'Ketchup (Gallon)', 'price' => 450.00, 'unit' => 'bottles'],
                ['name' => 'Ketchup (Sachet)', 'price' => 2.00, 'unit' => 'pcs'],
                ['name' => 'Mayonnaise (Gallon)', 'price' => 520.00, 'unit' => 'bottles'],
                ['name' => 'Mayonnaise (Sachet)', 'price' => 3.00, 'unit' => 'pcs'],
                ['name' => 'Hot Sauce', 'price' => 85.00, 'unit' => 'bottles'],
                ['name' => 'Soy Sauce (Gallon)', 'price' => 280.00, 'unit' => 'bottles'],
                ['name' => 'Soy Sauce (Sachet)', 'price' => 1.50, 'unit' => 'pcs'],
                ['name' => 'Vinegar (Gallon)', 'price' => 150.00, 'unit' => 'bottles'],
                ['name' => 'Gravy Mix', 'price' => 45.00, 'unit' => 'packs'],
                ['name' => 'Brown Gravy', 'price' => 50.00, 'unit' => 'packs'],
                ['name' => 'White Gravy', 'price' => 50.00, 'unit' => 'packs'],
                ['name' => 'BBQ Sauce', 'price' => 180.00, 'unit' => 'bottles'],
                ['name' => 'Honey Mustard', 'price' => 200.00, 'unit' => 'bottles'],
                ['name' => 'Ranch Dressing', 'price' => 220.00, 'unit' => 'bottles'],
                ['name' => 'Thousand Island', 'price' => 210.00, 'unit' => 'bottles'],
                ['name' => 'Caesar Dressing', 'price' => 230.00, 'unit' => 'bottles'],
                ['name' => 'Sweet Chili Sauce', 'price' => 150.00, 'unit' => 'bottles'],
                ['name' => 'Garlic Sauce', 'price' => 160.00, 'unit' => 'bottles'],
                ['name' => 'Cheese Sauce', 'price' => 180.00, 'unit' => 'bottles'],
                ['name' => 'Tartar Sauce', 'price' => 170.00, 'unit' => 'bottles'],
                ['name' => 'Buffalo Sauce', 'price' => 175.00, 'unit' => 'bottles'],
                ['name' => 'Teriyaki Sauce', 'price' => 180.00, 'unit' => 'bottles'],
            ],
            'Cooking Oils' => [
                ['name' => 'Vegetable Oil (20L)', 'price' => 1850.00, 'unit' => 'pcs'],
                ['name' => 'Vegetable Oil (5L)', 'price' => 480.00, 'unit' => 'pcs'],
                ['name' => 'Palm Oil (20L)', 'price' => 1650.00, 'unit' => 'pcs'],
                ['name' => 'Palm Oil (5L)', 'price' => 420.00, 'unit' => 'pcs'],
                ['name' => 'Canola Oil (5L)', 'price' => 500.00, 'unit' => 'pcs'],
                ['name' => 'Corn Oil (5L)', 'price' => 480.00, 'unit' => 'pcs'],
                ['name' => 'Coconut Oil (5L)', 'price' => 550.00, 'unit' => 'pcs'],
                ['name' => 'Olive Oil (1L)', 'price' => 350.00, 'unit' => 'bottles'],
                ['name' => 'Shortening', 'price' => 120.00, 'unit' => 'kg'],
                ['name' => 'Butter (Salted)', 'price' => 180.00, 'unit' => 'pcs'],
                ['name' => 'Butter (Unsalted)', 'price' => 185.00, 'unit' => 'pcs'],
                ['name' => 'Margarine', 'price' => 95.00, 'unit' => 'pcs'],
            ],
            'Seasonings & Spices' => [
                ['name' => 'Salt (1kg)', 'price' => 25.00, 'unit' => 'packs'],
                ['name' => 'Iodized Salt', 'price' => 28.00, 'unit' => 'packs'],
                ['name' => 'Rock Salt', 'price' => 30.00, 'unit' => 'packs'],
                ['name' => 'Black Pepper (Ground)', 'price' => 85.00, 'unit' => 'packs'],
                ['name' => 'White Pepper', 'price' => 90.00, 'unit' => 'packs'],
                ['name' => 'Garlic Powder', 'price' => 95.00, 'unit' => 'packs'],
                ['name' => 'Onion Powder', 'price' => 90.00, 'unit' => 'packs'],
                ['name' => 'Paprika', 'price' => 120.00, 'unit' => 'packs'],
                ['name' => 'Cayenne Pepper', 'price' => 110.00, 'unit' => 'packs'],
                ['name' => 'Chili Powder', 'price' => 100.00, 'unit' => 'packs'],
                ['name' => 'Chicken Seasoning', 'price' => 75.00, 'unit' => 'packs'],
                ['name' => 'All-Purpose Seasoning', 'price' => 80.00, 'unit' => 'packs'],
                ['name' => 'MSG', 'price' => 45.00, 'unit' => 'packs'],
                ['name' => 'Oregano', 'price' => 130.00, 'unit' => 'packs'],
                ['name' => 'Basil', 'price' => 125.00, 'unit' => 'packs'],
                ['name' => 'Thyme', 'price' => 135.00, 'unit' => 'packs'],
                ['name' => 'Rosemary', 'price' => 140.00, 'unit' => 'packs'],
                ['name' => 'Bay Leaves', 'price' => 115.00, 'unit' => 'packs'],
                ['name' => 'Cumin', 'price' => 120.00, 'unit' => 'packs'],
                ['name' => 'Curry Powder', 'price' => 110.00, 'unit' => 'packs'],
                ['name' => 'Five Spice', 'price' => 145.00, 'unit' => 'packs'],
                ['name' => 'Lemon Pepper', 'price' => 105.00, 'unit' => 'packs'],
                ['name' => 'Cajun Seasoning', 'price' => 150.00, 'unit' => 'packs'],
                ['name' => 'Italian Seasoning', 'price' => 125.00, 'unit' => 'packs'],
            ],
            'Rice & Grains' => [
                ['name' => 'White Rice (25kg)', 'price' => 1450.00, 'unit' => 'bags'],
                ['name' => 'White Rice (10kg)', 'price' => 580.00, 'unit' => 'bags'],
                ['name' => 'White Rice (5kg)', 'price' => 300.00, 'unit' => 'bags'],
                ['name' => 'Brown Rice (5kg)', 'price' => 350.00, 'unit' => 'bags'],
                ['name' => 'Java Rice Mix', 'price' => 65.00, 'unit' => 'packs'],
                ['name' => 'Garlic Rice Mix', 'price' => 55.00, 'unit' => 'packs'],
                ['name' => 'Fried Rice Mix', 'price' => 60.00, 'unit' => 'packs'],
                ['name' => 'Jasmine Rice (25kg)', 'price' => 1500.00, 'unit' => 'bags'],
                ['name' => 'Sticky Rice', 'price' => 320.00, 'unit' => 'bags'],
                ['name' => 'Corn Grits', 'price' => 280.00, 'unit' => 'bags'],
            ],
            'Vegetables & Produce' => [
                ['name' => 'Cabbage', 'price' => 45.00, 'unit' => 'kg'],
                ['name' => 'Carrots', 'price' => 60.00, 'unit' => 'kg'],
                ['name' => 'Onions (White)', 'price' => 80.00, 'unit' => 'kg'],
                ['name' => 'Onions (Red)', 'price' => 85.00, 'unit' => 'kg'],
                ['name' => 'Garlic (Bulb)', 'price' => 180.00, 'unit' => 'kg'],
                ['name' => 'Potatoes', 'price' => 55.00, 'unit' => 'kg'],
                ['name' => 'Lettuce', 'price' => 120.00, 'unit' => 'kg'],
                ['name' => 'Tomatoes', 'price' => 70.00, 'unit' => 'kg'],
                ['name' => 'Cucumber', 'price' => 50.00, 'unit' => 'kg'],
                ['name' => 'Bell Pepper (Green)', 'price' => 90.00, 'unit' => 'kg'],
                ['name' => 'Bell Pepper (Red)', 'price' => 95.00, 'unit' => 'kg'],
                ['name' => 'Celery', 'price' => 85.00, 'unit' => 'kg'],
                ['name' => 'Spring Onions', 'price' => 75.00, 'unit' => 'kg'],
                ['name' => 'Ginger', 'price' => 95.00, 'unit' => 'kg'],
                ['name' => 'Lemon', 'price' => 65.00, 'unit' => 'kg'],
                ['name' => 'Calamansi', 'price' => 70.00, 'unit' => 'kg'],
                ['name' => 'Chili (Siling Labuyo)', 'price' => 150.00, 'unit' => 'kg'],
                ['name' => 'Coleslaw Mix', 'price' => 95.00, 'unit' => 'kg'],
                ['name' => 'Pickles', 'price' => 95.00, 'unit' => 'kg'],
                ['name' => 'Corn (Canned)', 'price' => 45.00, 'unit' => 'cans'],
            ],
            'Bread & Bakery' => [
                ['name' => 'Burger Buns', 'price' => 15.00, 'unit' => 'pcs'],
                ['name' => 'Sandwich Bread', 'price' => 65.00, 'unit' => 'packs'],
                ['name' => 'Pandesal', 'price' => 5.00, 'unit' => 'pcs'],
                ['name' => 'Hotdog Buns', 'price' => 14.00, 'unit' => 'pcs'],
                ['name' => 'Dinner Rolls', 'price' => 12.00, 'unit' => 'pcs'],
                ['name' => 'Tortilla Wraps', 'price' => 12.00, 'unit' => 'pcs'],
                ['name' => 'Pita Bread', 'price' => 18.00, 'unit' => 'pcs'],
                ['name' => 'Garlic Bread', 'price' => 25.00, 'unit' => 'pcs'],
                ['name' => 'Breadcrumbs', 'price' => 85.00, 'unit' => 'packs'],
                ['name' => 'Croutons', 'price' => 75.00, 'unit' => 'packs'],
            ],
            'Dairy Products' => [
                ['name' => 'Butter (Salted)', 'price' => 180.00, 'unit' => 'pcs'],
                ['name' => 'Butter (Unsalted)', 'price' => 185.00, 'unit' => 'pcs'],
                ['name' => 'Cheese (Cheddar Block)', 'price' => 450.00, 'unit' => 'kg'],
                ['name' => 'Cheese (Sliced)', 'price' => 85.00, 'unit' => 'packs'],
                ['name' => 'Cheese (Parmesan)', 'price' => 550.00, 'unit' => 'kg'],
                ['name' => 'Cream Cheese', 'price' => 220.00, 'unit' => 'pcs'],
                ['name' => 'Milk (Fresh 1L)', 'price' => 95.00, 'unit' => 'bottles'],
                ['name' => 'Milk (Evaporated)', 'price' => 45.00, 'unit' => 'cans'],
                ['name' => 'Milk (Condensed)', 'price' => 55.00, 'unit' => 'cans'],
                ['name' => 'Heavy Cream', 'price' => 180.00, 'unit' => 'bottles'],
                ['name' => 'Sour Cream', 'price' => 160.00, 'unit' => 'bottles'],
                ['name' => 'Whipped Cream', 'price' => 140.00, 'unit' => 'bottles'],
                ['name' => 'Yogurt', 'price' => 75.00, 'unit' => 'bottles'],
            ],
            'Frozen Goods' => [
                ['name' => 'Frozen Fries (Regular)', 'price' => 250.00, 'unit' => 'packs'],
                ['name' => 'Frozen Fries (Curly)', 'price' => 280.00, 'unit' => 'packs'],
                ['name' => 'Frozen Fries (Wedges)', 'price' => 290.00, 'unit' => 'packs'],
                ['name' => 'Frozen Corn', 'price' => 120.00, 'unit' => 'packs'],
                ['name' => 'Frozen Peas', 'price' => 125.00, 'unit' => 'packs'],
                ['name' => 'Frozen Mixed Vegetables', 'price' => 130.00, 'unit' => 'packs'],
                ['name' => 'Ice Cream (Vanilla)', 'price' => 350.00, 'unit' => 'pcs'],
                ['name' => 'Ice Cream (Chocolate)', 'price' => 350.00, 'unit' => 'pcs'],
                ['name' => 'Ice Cream (Strawberry)', 'price' => 350.00, 'unit' => 'pcs'],
                ['name' => 'Frozen Mozzarella Sticks', 'price' => 320.00, 'unit' => 'packs'],
            ],
            'Packaging & Supplies' => [
                ['name' => 'Takeout Box (Small)', 'price' => 8.00, 'unit' => 'pcs'],
                ['name' => 'Takeout Box (Medium)', 'price' => 12.00, 'unit' => 'pcs'],
                ['name' => 'Takeout Box (Large)', 'price' => 18.00, 'unit' => 'pcs'],
                ['name' => 'Paper Bag (Small)', 'price' => 3.00, 'unit' => 'pcs'],
                ['name' => 'Paper Bag (Large)', 'price' => 5.00, 'unit' => 'pcs'],
                ['name' => 'Plastic Container (Round)', 'price' => 15.00, 'unit' => 'pcs'],
                ['name' => 'Plastic Container (Rectangle)', 'price' => 18.00, 'unit' => 'pcs'],
                ['name' => 'Styrofoam Box', 'price' => 10.00, 'unit' => 'pcs'],
                ['name' => 'Plastic Utensils Set', 'price' => 5.00, 'unit' => 'pcs'],
                ['name' => 'Plastic Fork', 'price' => 0.50, 'unit' => 'pcs'],
                ['name' => 'Plastic Spoon', 'price' => 0.50, 'unit' => 'pcs'],
                ['name' => 'Plastic Knife', 'price' => 0.50, 'unit' => 'pcs'],
                ['name' => 'Drinking Straws', 'price' => 0.50, 'unit' => 'pcs'],
                ['name' => 'Cup (8oz)', 'price' => 3.00, 'unit' => 'pcs'],
                ['name' => 'Cup (12oz)', 'price' => 4.00, 'unit' => 'pcs'],
                ['name' => 'Cup (16oz)', 'price' => 5.00, 'unit' => 'pcs'],
                ['name' => 'Cup Lid', 'price' => 1.00, 'unit' => 'pcs'],
                ['name' => 'Sauce Cup (Small)', 'price' => 2.00, 'unit' => 'pcs'],
                ['name' => 'Aluminum Foil', 'price' => 250.00, 'unit' => 'rolls'],
                ['name' => 'Cling Wrap', 'price' => 180.00, 'unit' => 'rolls'],
                ['name' => 'Wax Paper', 'price' => 120.00, 'unit' => 'rolls'],
            ],
            'Cleaning & Sanitation' => [
                ['name' => 'Dish Soap (Gallon)', 'price' => 350.00, 'unit' => 'bottles'],
                ['name' => 'Dish Soap (500ml)', 'price' => 120.00, 'unit' => 'bottles'],
                ['name' => 'Hand Soap', 'price' => 85.00, 'unit' => 'bottles'],
                ['name' => 'Hand Sanitizer', 'price' => 150.00, 'unit' => 'bottles'],
                ['name' => 'Bleach', 'price' => 180.00, 'unit' => 'bottles'],
                ['name' => 'Floor Cleaner', 'price' => 200.00, 'unit' => 'bottles'],
                ['name' => 'Glass Cleaner', 'price' => 150.00, 'unit' => 'bottles'],
                ['name' => 'Degreaser', 'price' => 280.00, 'unit' => 'bottles'],
                ['name' => 'Disinfectant Spray', 'price' => 220.00, 'unit' => 'bottles'],
                ['name' => 'Sponge', 'price' => 25.00, 'unit' => 'pcs'],
                ['name' => 'Steel Wool', 'price' => 35.00, 'unit' => 'pcs'],
                ['name' => 'Scrub Brush', 'price' => 45.00, 'unit' => 'pcs'],
                ['name' => 'Mop Head', 'price' => 120.00, 'unit' => 'pcs'],
                ['name' => 'Trash Bags (Large)', 'price' => 85.00, 'unit' => 'packs'],
                ['name' => 'Trash Bags (Small)', 'price' => 45.00, 'unit' => 'packs'],
                ['name' => 'Rubber Gloves', 'price' => 150.00, 'unit' => 'pairs'],
                ['name' => 'Disposable Gloves', 'price' => 250.00, 'unit' => 'boxes'],
            ],
            'Eggs' => [
                ['name' => 'Eggs (Tray - 30pcs)', 'price' => 220.00, 'unit' => 'pcs'],
                ['name' => 'Eggs (Dozen)', 'price' => 95.00, 'unit' => 'pcs'],
                ['name' => 'Eggs (Half Dozen)', 'price' => 50.00, 'unit' => 'pcs'],
                ['name' => 'Quail Eggs', 'price' => 180.00, 'unit' => 'tray'],
                ['name' => 'Salted Eggs', 'price' => 120.00, 'unit' => 'pcs'],
                ['name' => 'Century Eggs', 'price' => 150.00, 'unit' => 'pcs'],
            ],
            'Flour & Breading' => [
                ['name' => 'All-Purpose Flour (25kg)', 'price' => 950.00, 'unit' => 'bags'],
                ['name' => 'All-Purpose Flour (1kg)', 'price' => 55.00, 'unit' => 'packs'],
                ['name' => 'Breading Mix', 'price' => 120.00, 'unit' => 'packs'],
                ['name' => 'Cornstarch', 'price' => 45.00, 'unit' => 'packs'],
                ['name' => 'Batter Mix', 'price' => 110.00, 'unit' => 'packs'],
                ['name' => 'Tempura Flour', 'price' => 130.00, 'unit' => 'packs'],
                ['name' => 'Panko Breadcrumbs', 'price' => 150.00, 'unit' => 'packs'],
                ['name' => 'Seasoned Flour', 'price' => 100.00, 'unit' => 'packs'],
                ['name' => 'Cake Flour', 'price' => 60.00, 'unit' => 'packs'],
                ['name' => 'Bread Flour', 'price' => 65.00, 'unit' => 'packs'],
            ],
            'Marinades & Brines' => [
                ['name' => 'Chicken Marinade', 'price' => 180.00, 'unit' => 'bottles'],
                ['name' => 'BBQ Marinade', 'price' => 195.00, 'unit' => 'bottles'],
                ['name' => 'Teriyaki Marinade', 'price' => 185.00, 'unit' => 'bottles'],
                ['name' => 'Honey Garlic Marinade', 'price' => 200.00, 'unit' => 'bottles'],
                ['name' => 'Lemon Herb Marinade', 'price' => 190.00, 'unit' => 'bottles'],
                ['name' => 'Spicy Marinade', 'price' => 175.00, 'unit' => 'bottles'],
                ['name' => 'Buttermilk Brine', 'price' => 220.00, 'unit' => 'bottles'],
                ['name' => 'Salt Brine Mix', 'price' => 85.00, 'unit' => 'packs'],
                ['name' => 'Pickle Juice', 'price' => 95.00, 'unit' => 'bottles'],
                ['name' => 'Soy Garlic Marinade', 'price' => 165.00, 'unit' => 'bottles'],
            ],
            'Side Dishes' => [
                ['name' => 'Coleslaw (Pre-made)', 'price' => 150.00, 'unit' => 'kg'],
                ['name' => 'Mashed Potato Mix', 'price' => 85.00, 'unit' => 'packs'],
                ['name' => 'Gravy (Pre-made)', 'price' => 120.00, 'unit' => 'bottles'],
                ['name' => 'Corn on the Cob', 'price' => 35.00, 'unit' => 'pcs'],
                ['name' => 'Baked Beans', 'price' => 55.00, 'unit' => 'cans'],
                ['name' => 'Mac and Cheese Mix', 'price' => 95.00, 'unit' => 'packs'],
                ['name' => 'Potato Salad', 'price' => 140.00, 'unit' => 'kg'],
                ['name' => 'Garden Salad Mix', 'price' => 110.00, 'unit' => 'kg'],
            ],
            'Desserts & Sweets' => [
                ['name' => 'Chocolate Syrup', 'price' => 180.00, 'unit' => 'bottles'],
                ['name' => 'Caramel Syrup', 'price' => 195.00, 'unit' => 'bottles'],
                ['name' => 'Strawberry Syrup', 'price' => 185.00, 'unit' => 'bottles'],
                ['name' => 'Sundae Toppings', 'price' => 85.00, 'unit' => 'packs'],
                ['name' => 'Sprinkles', 'price' => 65.00, 'unit' => 'packs'],
                ['name' => 'Whipped Cream', 'price' => 140.00, 'unit' => 'bottles'],
                ['name' => 'Brownie Mix', 'price' => 120.00, 'unit' => 'packs'],
                ['name' => 'Cake Mix', 'price' => 110.00, 'unit' => 'packs'],
                ['name' => 'Cookies', 'price' => 75.00, 'unit' => 'packs'],
                ['name' => 'Ice Cream Cones', 'price' => 45.00, 'unit' => 'pcs'],
                ['name' => 'Fruit Cocktail', 'price' => 55.00, 'unit' => 'cans'],
                ['name' => 'Leche Flan Mix', 'price' => 95.00, 'unit' => 'packs'],
            ],
            'Paper Products' => [
                ['name' => 'Napkins (Pack)', 'price' => 45.00, 'unit' => 'packs'],
                ['name' => 'Paper Towels (Roll)', 'price' => 65.00, 'unit' => 'rolls'],
                ['name' => 'Tissue Paper (Box)', 'price' => 85.00, 'unit' => 'boxes'],
                ['name' => 'Toilet Paper', 'price' => 75.00, 'unit' => 'rolls'],
                ['name' => 'Paper Plates', 'price' => 35.00, 'unit' => 'packs'],
                ['name' => 'Paper Cups', 'price' => 25.00, 'unit' => 'packs'],
                ['name' => 'Parchment Paper', 'price' => 180.00, 'unit' => 'rolls'],
                ['name' => 'Wax Paper Sheets', 'price' => 120.00, 'unit' => 'packs'],
            ],
            'Kitchen Equipment' => [
                ['name' => 'Tongs', 'price' => 150.00, 'unit' => 'pcs'],
                ['name' => 'Spatula', 'price' => 120.00, 'unit' => 'pcs'],
                ['name' => 'Ladle', 'price' => 100.00, 'unit' => 'pcs'],
                ['name' => 'Measuring Cups', 'price' => 180.00, 'unit' => 'set'],
                ['name' => 'Measuring Spoons', 'price' => 95.00, 'unit' => 'set'],
                ['name' => 'Thermometer', 'price' => 350.00, 'unit' => 'pcs'],
                ['name' => 'Timer', 'price' => 250.00, 'unit' => 'pcs'],
                ['name' => 'Cutting Board', 'price' => 280.00, 'unit' => 'pcs'],
                ['name' => 'Chef Knife', 'price' => 450.00, 'unit' => 'pcs'],
                ['name' => 'Peeler', 'price' => 85.00, 'unit' => 'pcs'],
                ['name' => 'Can Opener', 'price' => 120.00, 'unit' => 'pcs'],
                ['name' => 'Whisk', 'price' => 95.00, 'unit' => 'pcs'],
                ['name' => 'Mixing Bowl', 'price' => 150.00, 'unit' => 'pcs'],
                ['name' => 'Strainer', 'price' => 180.00, 'unit' => 'pcs'],
                ['name' => 'Colander', 'price' => 200.00, 'unit' => 'pcs'],
            ],
            'Uniforms & Apparel' => [
                ['name' => 'Chef Hat', 'price' => 85.00, 'unit' => 'pcs'],
                ['name' => 'Hair Net', 'price' => 5.00, 'unit' => 'pcs'],
                ['name' => 'Apron', 'price' => 180.00, 'unit' => 'pcs'],
                ['name' => 'Kitchen Gloves', 'price' => 95.00, 'unit' => 'pcs'],
                ['name' => 'Safety Shoes', 'price' => 850.00, 'unit' => 'pairs'],
                ['name' => 'Polo Shirt (Staff)', 'price' => 350.00, 'unit' => 'pcs'],
                ['name' => 'Name Tag', 'price' => 25.00, 'unit' => 'pcs'],
                ['name' => 'Face Mask', 'price' => 150.00, 'unit' => 'boxes'],
            ],
        ];

        $tbl = $this->db->table('products');
        $insertCount = 0;
        $updateCount = 0;
        $priceUpdateCount = 0;

        foreach ($productsByCategory as $categoryName => $products) {
            $categoryId = $categoryMap[$categoryName] ?? null;
            
            if (!$categoryId) {
                echo "⚠️ Category not found: {$categoryName}\n";
                continue;
            }

            foreach ($products as $p) {
                $productName = $p['name'];
                $productPrice = (float)($p['price'] ?? 0);
                $productUnit = $p['unit'] ?? 'pcs';

                // Find existing product by name (case-insensitive, across all branches)
                $existing = $tbl->where('LOWER(name)', strtolower($productName))
                    ->get()
                    ->getRowArray();

                if ($existing) {
                    // Update price if missing, NULL, or zero
                    $existingPrice = (float)($existing['price'] ?? 0);
                    $needsUpdate = false;
                    $updateData = [];
                    
                    if ($existingPrice == 0 || $existing['price'] === null) {
                        $updateData['price'] = $productPrice;
                        $needsUpdate = true;
                    }
                    
                    // Ensure unit is set
                    if (empty($existing['unit'])) {
                        $updateData['unit'] = $productUnit ?: ($existing['unit'] ?? 'pcs');
                        $needsUpdate = true;
                    }
                    
                    // Ensure created_by is set (not NULL)
                    if (empty($existing['created_by']) || $existing['created_by'] === null) {
                        $updateData['created_by'] = $defaultUser;
                        $needsUpdate = true;
                    }
                    
                    if ($needsUpdate) {
                        $updateData['updated_at'] = $now;
                        $tbl->where('id', $existing['id'])->update($updateData);
                        $priceUpdateCount++;
                        if (isset($updateData['price'])) {
                            echo "✅ Updated price for: {$productName} = ₱{$productPrice}\n";
                        }
                        if (isset($updateData['created_by'])) {
                            echo "✅ Updated created_by for: {$productName}\n";
                        }
                    }
                } else {
                    // Create new product if it doesn't exist
                    $row = [
                        'name' => $productName,
                        'category_id' => $categoryId,
                        'branch_id' => $defaultBranchId,
                        'created_by' => $defaultUser,
                        'stock_qty' => 0,
                        'unit' => $productUnit,
                        'price' => $productPrice,
                        'min_stock' => 0,
                        'max_stock' => 0,
                        'expiry' => null,
                        'status' => 'active',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    $tbl->insert($row);
                    $insertCount++;
                    echo "✅ Created product: {$productName} = ₱{$productPrice}\n";
                }
            }
            
            echo "✅ Processed category: {$categoryName}\n";
        }

        echo "\n📊 Summary:\n";
        echo "   - Inserted {$insertCount} new products\n";
        echo "   - Updated prices for {$priceUpdateCount} existing products\n";
        echo "   - All products now have unit prices!\n";
    }
}


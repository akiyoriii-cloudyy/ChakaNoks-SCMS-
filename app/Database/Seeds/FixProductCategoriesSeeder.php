<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder to fix products with missing or incorrect category_id
 * This maps product names to their correct categories
 */
class FixProductCategoriesSeeder extends Seeder
{
    public function run()
    {
        echo "=== Fixing Product Categories ===\n\n";

        // Get all categories
        $categories = $this->db->table('categories')->get()->getResultArray();
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[strtolower(trim($cat['name']))] = $cat['id'];
        }

        echo "Found " . count($categoryMap) . " categories\n";

        // Define product to category mappings
        $productCategoryMappings = [
            // Chicken Parts
            'Chicken Parts' => [
                'Whole Chicken', 'Chicken Breast', 'Chicken Thigh', 'Chicken Wings', 
                'Chicken Drumstick', 'Chicken Liver', 'Chicken Gizzard', 'Chicken Feet',
                'Chicken Head', 'Chicken Neck', 'Chicken Back', 'Chicken Heart',
                'Chicken Kidney', 'Chicken Intestine', 'Chicken Blood', 'Chicken Skin',
                'Chicken Fat', 'Chicken Bones', 'Chicken Tail', 'Chicken Leg Quarter',
                'Chicken Breast Fillet', 'Chicken Tenderloin', 'Chicken Wing Tip',
                'Chicken Wing Flat', 'Chicken Wing Drumlette', 'Ground Chicken', 'Chicken Cutlet'
            ],
            // Beverages
            'Beverages' => [
                'Coca-Cola', 'Sprite', 'Royal', 'Pepsi', 'Mountain Dew', 'Iced Tea',
                'Bottled Water', 'Juice', 'Coffee', 'Tea', 'Soda', 'Lemonade'
            ],
            // Condiments & Sauces
            'Condiments & Sauces' => [
                'Ketchup', 'Mustard', 'Mayonnaise', 'Hot Sauce', 'Soy Sauce',
                'Vinegar', 'Gravy', 'BBQ Sauce', 'Honey Mustard', 'Ranch Dressing',
                'Gravy Mix', 'Sweet Chili Sauce', 'Banana Ketchup'
            ],
            // Seasonings & Spices
            'Seasonings & Spices' => [
                'Salt', 'Pepper', 'Garlic Powder', 'Onion Powder', 'Paprika',
                'Cayenne', 'Chili Powder', 'Chicken Seasoning', 'All-Purpose Seasoning',
                'MSG', 'Oregano', 'Thyme', 'Rosemary', 'Cumin', 'Coriander',
                'Cajun Seasoning', 'Italian Seasoning', 'Five Spice', 'Bay Leaves',
                'Black Pepper'
            ],
            // Vegetables & Produce
            'Vegetables & Produce' => [
                'Potatoes', 'Onions', 'Garlic', 'Tomatoes', 'Lettuce', 'Cabbage',
                'Carrots', 'Celery', 'Bell Peppers', 'Jalapeños', 'Corn', 'Coleslaw',
                'Green Beans', 'Pickles', 'Cucumber', 'Mushrooms'
            ],
            // Dairy Products
            'Dairy Products' => [
                'Butter', 'Cheese', 'Milk', 'Cream', 'Eggs', 'Sour Cream',
                'Whipped Cream', 'Yogurt', 'Cream Cheese', 'Heavy Cream',
                'Buttermilk', 'Cheddar', 'Mozzarella', 'Parmesan'
            ],
            // Frozen Goods
            'Frozen Goods' => [
                'Ice Cream', 'Frozen Fries', 'Frozen Vegetables', 'Frozen Chicken',
                'Ice', 'Frozen Desserts', 'Frozen Fish', 'Frozen Patties'
            ],
            // Oils & Fats
            'Oils & Fats' => [
                'Cooking Oil', 'Vegetable Oil', 'Canola Oil', 'Olive Oil',
                'Coconut Oil', 'Palm Oil', 'Lard', 'Shortening', 'Frying Oil'
            ],
            // Breading & Coating
            'Breading & Coating' => [
                'Flour', 'Breadcrumbs', 'Panko', 'Cornstarch', 'Batter Mix',
                'Coating Mix', 'Tempura Mix', 'Crispy Coating'
            ],
            // Marinades & Brines
            'Marinades & Brines' => [
                'Chicken Marinade', 'BBQ Marinade', 'Teriyaki Marinade',
                'Buttermilk Brine', 'Soy Garlic Marinade', 'Honey Garlic Marinade',
                'Citrus Marinade', 'Spicy Marinade'
            ],
            // Rice & Grains
            'Rice & Grains' => [
                'Rice', 'White Rice', 'Brown Rice', 'Jasmine Rice', 'Fried Rice',
                'Fried Rice Mix', 'Garlic Rice Mix', 'Java Rice Mix', 'Sticky Rice',
                'Corn Grits', 'Oats', 'Quinoa'
            ],
            // Pasta & Noodles
            'Pasta & Noodles' => [
                'Pasta', 'Spaghetti', 'Macaroni', 'Noodles', 'Egg Noodles',
                'Rice Noodles', 'Lasagna', 'Fettuccine'
            ],
            // Packaging & Disposables
            'Packaging & Disposables' => [
                'Paper Bags', 'Plastic Bags', 'Food Containers', 'Napkins',
                'Straws', 'Cups', 'Plates', 'Cutlery', 'Takeout Boxes',
                'Aluminum Foil', 'Cling Wrap', 'Paper Plates'
            ],
            // Cleaning Supplies
            'Cleaning Supplies' => [
                'Dish Soap', 'Sanitizer', 'Bleach', 'Floor Cleaner', 'Degreaser',
                'Hand Soap', 'Paper Towels', 'Trash Bags', 'Sponges', 'Gloves'
            ],
            // Baking Ingredients
            'Baking Ingredients' => [
                'Baking Powder', 'Baking Soda', 'Yeast', 'Vanilla Extract',
                'Sugar', 'Brown Sugar', 'Honey', 'Molasses', 'Maple Syrup',
                'All-Purpose Flour', 'Bread Flour', 'Cake Flour'
            ],
            // Canned Goods
            'Canned Goods' => [
                'Canned Corn', 'Canned Beans', 'Canned Tomatoes', 'Canned Mushrooms',
                'Tomato Paste', 'Tomato Sauce', 'Coconut Milk', 'Condensed Milk'
            ],
            // Fresh Herbs
            'Fresh Herbs' => [
                'Fresh Basil', 'Fresh Parsley', 'Fresh Cilantro', 'Fresh Thyme',
                'Fresh Rosemary', 'Fresh Mint', 'Fresh Dill', 'Lemongrass',
                'Green Onions', 'Chives'
            ],
            // Sides & Accompaniments
            'Sides & Accompaniments' => [
                'Mashed Potatoes', 'French Fries', 'Onion Rings', 'Corn on the Cob',
                'Coleslaw Mix', 'Biscuits', 'Cornbread', 'Dinner Rolls'
            ],
            // Desserts & Sweets
            'Desserts & Sweets' => [
                'Chocolate', 'Cookies', 'Cake', 'Pie', 'Brownie',
                'Whipped Topping', 'Sprinkles', 'Chocolate Chips', 'Caramel Sauce'
            ],
            // Dry Goods & Staples
            'Dry Goods & Staples' => [
                'Crackers', 'Chips', 'Pretzels', 'Nuts', 'Seeds',
                'Dried Fruits', 'Cereal', 'Granola'
            ],
        ];

        $updated = 0;
        $notFound = [];

        // Get all products
        $products = $this->db->table('products')->get()->getResultArray();
        echo "Found " . count($products) . " products to check\n\n";

        foreach ($products as $product) {
            $productName = trim($product['name']);
            $productNameLower = strtolower($productName);
            $currentCategoryId = $product['category_id'];
            $foundCategory = null;

            // Find the correct category for this product
            foreach ($productCategoryMappings as $categoryName => $productNames) {
                foreach ($productNames as $mappedName) {
                    // Check for exact match or partial match
                    $mappedNameLower = strtolower($mappedName);
                    if ($productNameLower === $mappedNameLower || 
                        strpos($productNameLower, $mappedNameLower) !== false ||
                        strpos($mappedNameLower, $productNameLower) !== false) {
                        $foundCategory = $categoryName;
                        break 2;
                    }
                }
            }

            if ($foundCategory) {
                $categoryKey = strtolower($foundCategory);
                if (isset($categoryMap[$categoryKey])) {
                    $newCategoryId = $categoryMap[$categoryKey];
                    
                    // Update if category is missing or different
                    if (empty($currentCategoryId) || $currentCategoryId != $newCategoryId) {
                        $this->db->table('products')
                            ->where('id', $product['id'])
                            ->update(['category_id' => $newCategoryId]);
                        
                        echo "✅ Updated: {$productName} -> {$foundCategory} (ID: {$newCategoryId})\n";
                        $updated++;
                    }
                }
            } else if (empty($currentCategoryId)) {
                $notFound[] = $productName;
            }
        }

        echo "\n=== Summary ===\n";
        echo "Updated: {$updated} products\n";
        
        if (count($notFound) > 0) {
            echo "\n⚠️ Products without category mapping:\n";
            foreach (array_unique($notFound) as $name) {
                echo "  - {$name}\n";
            }
        }

        echo "\n✅ Category fix completed!\n";
    }
}


/**
 * Category-based item options for ChakaNoks Chicken Restaurant
 * This file contains all product categories and their items for use across the system
 */

const CHAKANOKS_CATEGORY_ITEMS = {
    'Chicken Parts': [
        'Whole Chicken', 'Chicken Breast', 'Chicken Thigh', 'Chicken Wings', 'Chicken Drumstick',
        'Chicken Liver', 'Chicken Gizzard', 'Chicken Feet', 'Chicken Head', 'Chicken Neck',
        'Chicken Back', 'Chicken Heart', 'Chicken Kidney', 'Chicken Intestine', 'Chicken Blood',
        'Chicken Skin', 'Chicken Fat', 'Chicken Bones', 'Chicken Tail', 'Chicken Leg Quarter',
        'Chicken Breast Fillet', 'Chicken Tenderloin', 'Chicken Wing Tip', 'Chicken Wing Flat',
        'Chicken Wing Drumlette', 'Ground Chicken', 'Chicken Cutlet'
    ],
    'Beverages': [
        'Coca-Cola (1.5L)', 'Coca-Cola (330ml can)', 'Sprite (1.5L)', 'Sprite (330ml can)',
        'Royal (1.5L)', 'Royal (330ml can)', 'Pepsi (1.5L)', 'Mountain Dew (1.5L)',
        'Bottled Water (500ml)', 'Bottled Water (1L)', 'Mineral Water (6L)',
        'Iced Tea Powder', 'Orange Juice', 'Pineapple Juice', 'Mango Juice',
        'Coffee (3-in-1)', 'Hot Chocolate Mix', 'Lemonade Mix', 'Fruit Punch',
        'Energy Drink', 'Yakult', 'Fresh Milk (1L)', 'Chocolate Milk'
    ],
    'Condiments & Sauces': [
        'Ketchup (Gallon)', 'Ketchup (Sachet)', 'Mayonnaise (Gallon)', 'Mayonnaise (Sachet)',
        'Hot Sauce', 'Soy Sauce (Gallon)', 'Soy Sauce (Sachet)', 'Vinegar (Gallon)',
        'Gravy Mix', 'Brown Gravy', 'White Gravy', 'BBQ Sauce', 'Honey Mustard',
        'Ranch Dressing', 'Thousand Island', 'Caesar Dressing', 'Sweet Chili Sauce',
        'Garlic Sauce', 'Cheese Sauce', 'Tartar Sauce', 'Buffalo Sauce', 'Teriyaki Sauce'
    ],
    'Cooking Oils': [
        'Vegetable Oil (20L)', 'Vegetable Oil (5L)', 'Palm Oil (20L)', 'Palm Oil (5L)',
        'Canola Oil (5L)', 'Corn Oil (5L)', 'Coconut Oil (5L)', 'Olive Oil (1L)',
        'Shortening', 'Butter (Salted)', 'Butter (Unsalted)', 'Margarine'
    ],
    'Seasonings & Spices': [
        'Salt (1kg)', 'Iodized Salt', 'Rock Salt', 'Black Pepper (Ground)', 'White Pepper',
        'Garlic Powder', 'Onion Powder', 'Paprika', 'Cayenne Pepper', 'Chili Powder',
        'Chicken Seasoning', 'All-Purpose Seasoning', 'MSG', 'Oregano', 'Basil',
        'Thyme', 'Rosemary', 'Bay Leaves', 'Cumin', 'Curry Powder', 'Five Spice',
        'Lemon Pepper', 'Cajun Seasoning', 'Italian Seasoning'
    ],
    'Rice & Grains': [
        'White Rice (25kg)', 'White Rice (10kg)', 'White Rice (5kg)',
        'Brown Rice (5kg)', 'Java Rice Mix', 'Garlic Rice Mix', 'Fried Rice Mix',
        'Jasmine Rice (25kg)', 'Sticky Rice', 'Corn Grits'
    ],
    'Vegetables & Produce': [
        'Cabbage', 'Carrots', 'Onions (White)', 'Onions (Red)', 'Garlic (Bulb)',
        'Potatoes', 'Lettuce', 'Tomatoes', 'Cucumber', 'Bell Pepper (Green)',
        'Bell Pepper (Red)', 'Celery', 'Spring Onions', 'Ginger', 'Lemon',
        'Calamansi', 'Chili (Siling Labuyo)', 'Coleslaw Mix', 'Pickles', 'Corn (Canned)'
    ],
    'Bread & Bakery': [
        'Burger Buns', 'Sandwich Bread', 'Pandesal', 'Hotdog Buns', 'Dinner Rolls',
        'Tortilla Wraps', 'Pita Bread', 'Garlic Bread', 'Breadcrumbs', 'Croutons'
    ],
    'Dairy Products': [
        'Butter (Salted)', 'Butter (Unsalted)', 'Cheese (Cheddar Block)', 'Cheese (Sliced)',
        'Cheese (Parmesan)', 'Cream Cheese', 'Milk (Fresh 1L)', 'Milk (Evaporated)',
        'Milk (Condensed)', 'Heavy Cream', 'Sour Cream', 'Whipped Cream', 'Yogurt'
    ],
    'Frozen Goods': [
        'Frozen Fries (Regular)', 'Frozen Fries (Curly)', 'Frozen Fries (Wedges)',
        'Frozen Corn', 'Frozen Peas', 'Frozen Mixed Vegetables', 'Ice Cream (Vanilla)',
        'Ice Cream (Chocolate)', 'Ice Cream (Strawberry)', 'Frozen Mozzarella Sticks'
    ],
    'Packaging & Supplies': [
        'Takeout Box (Small)', 'Takeout Box (Medium)', 'Takeout Box (Large)',
        'Paper Bag (Small)', 'Paper Bag (Large)', 'Plastic Container (Round)',
        'Plastic Container (Rectangle)', 'Styrofoam Box', 'Plastic Utensils Set',
        'Plastic Fork', 'Plastic Spoon', 'Plastic Knife', 'Drinking Straws',
        'Cup (8oz)', 'Cup (12oz)', 'Cup (16oz)', 'Cup Lid', 'Sauce Cup (Small)',
        'Aluminum Foil', 'Cling Wrap', 'Wax Paper'
    ],
    'Cleaning & Sanitation': [
        'Dish Soap (Gallon)', 'Dish Soap (500ml)', 'Hand Soap', 'Hand Sanitizer',
        'Bleach', 'Floor Cleaner', 'Glass Cleaner', 'Degreaser', 'Disinfectant Spray',
        'Sponge', 'Steel Wool', 'Scrub Brush', 'Mop Head', 'Trash Bags (Large)',
        'Trash Bags (Small)', 'Rubber Gloves', 'Disposable Gloves'
    ],
    'Eggs': [
        'Eggs (Tray - 30pcs)', 'Eggs (Dozen)', 'Eggs (Half Dozen)', 'Quail Eggs',
        'Salted Eggs', 'Century Eggs'
    ],
    'Flour & Breading': [
        'All-Purpose Flour (25kg)', 'All-Purpose Flour (1kg)', 'Breading Mix',
        'Cornstarch', 'Batter Mix', 'Tempura Flour', 'Panko Breadcrumbs',
        'Seasoned Flour', 'Cake Flour', 'Bread Flour'
    ],
    'Marinades & Brines': [
        'Chicken Marinade', 'BBQ Marinade', 'Teriyaki Marinade', 'Honey Garlic Marinade',
        'Lemon Herb Marinade', 'Spicy Marinade', 'Buttermilk Brine', 'Salt Brine Mix',
        'Pickle Juice', 'Soy Garlic Marinade'
    ],
    'Side Dishes': [
        'Coleslaw (Pre-made)', 'Mashed Potato Mix', 'Gravy (Pre-made)', 'Corn on the Cob',
        'Baked Beans', 'Mac and Cheese Mix', 'Potato Salad', 'Garden Salad Mix'
    ],
    'Desserts & Sweets': [
        'Chocolate Syrup', 'Caramel Syrup', 'Strawberry Syrup', 'Sundae Toppings',
        'Sprinkles', 'Whipped Cream', 'Brownie Mix', 'Cake Mix', 'Cookies',
        'Ice Cream Cones', 'Fruit Cocktail', 'Leche Flan Mix'
    ],
    'Paper Products': [
        'Napkins (Pack)', 'Paper Towels (Roll)', 'Tissue Paper (Box)', 'Toilet Paper',
        'Paper Plates', 'Paper Cups', 'Parchment Paper', 'Wax Paper Sheets'
    ],
    'Kitchen Equipment': [
        'Tongs', 'Spatula', 'Ladle', 'Measuring Cups', 'Measuring Spoons',
        'Thermometer', 'Timer', 'Cutting Board', 'Chef Knife', 'Peeler',
        'Can Opener', 'Whisk', 'Mixing Bowl', 'Strainer', 'Colander'
    ],
    'Uniforms & Apparel': [
        'Chef Hat', 'Hair Net', 'Apron', 'Kitchen Gloves', 'Safety Shoes',
        'Polo Shirt (Staff)', 'Name Tag', 'Face Mask'
    ]
};

// Default units for each category
const CHAKANOKS_CATEGORY_UNITS = {
    'Chicken Parts': 'kg',
    'Beverages': 'bottles',
    'Condiments & Sauces': 'bottles',
    'Cooking Oils': 'liters',
    'Seasonings & Spices': 'packs',
    'Rice & Grains': 'kg',
    'Vegetables & Produce': 'kg',
    'Bread & Bakery': 'pcs',
    'Dairy Products': 'pcs',
    'Frozen Goods': 'packs',
    'Packaging & Supplies': 'pcs',
    'Cleaning & Sanitation': 'bottles',
    'Eggs': 'pcs',
    'Flour & Breading': 'kg',
    'Marinades & Brines': 'bottles',
    'Side Dishes': 'pcs',
    'Desserts & Sweets': 'pcs',
    'Paper Products': 'packs',
    'Kitchen Equipment': 'pcs',
    'Uniforms & Apparel': 'pcs'
};

// All available categories
const CHAKANOKS_CATEGORIES = Object.keys(CHAKANOKS_CATEGORY_ITEMS);

/**
 * Get items for a specific category
 * @param {string} category - Category name
 * @returns {Array} Array of item names
 */
function getCategoryItems(category) {
    return CHAKANOKS_CATEGORY_ITEMS[category] || [];
}

/**
 * Get default unit for a category
 * @param {string} category - Category name
 * @returns {string} Default unit
 */
function getCategoryDefaultUnit(category) {
    return CHAKANOKS_CATEGORY_UNITS[category] || 'pcs';
}

/**
 * Populate a select element with category items
 * @param {HTMLSelectElement} selectElement - The select element to populate
 * @param {string} category - Category name
 * @param {boolean} includeOther - Whether to include "Other" option
 */
function populateCategoryItems(selectElement, category, includeOther = true) {
    selectElement.innerHTML = '<option value="">Select Item</option>';
    
    const items = getCategoryItems(category);
    items.forEach(function(item) {
        const option = document.createElement('option');
        option.value = item;
        option.textContent = item;
        selectElement.appendChild(option);
    });
    
    if (includeOther) {
        const otherOption = document.createElement('option');
        otherOption.value = '__other__';
        otherOption.textContent = '-- Other (Custom Item) --';
        selectElement.appendChild(otherOption);
    }
}

/**
 * Search for items across all categories
 * @param {string} query - Search query
 * @returns {Array} Array of matching items with their categories
 */
function searchItems(query) {
    const results = [];
    const lowerQuery = query.toLowerCase();
    
    for (const [category, items] of Object.entries(CHAKANOKS_CATEGORY_ITEMS)) {
        items.forEach(function(item) {
            if (item.toLowerCase().includes(lowerQuery)) {
                results.push({ item, category });
            }
        });
    }
    
    return results;
}

// Export for module systems (if needed)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        CHAKANOKS_CATEGORY_ITEMS,
        CHAKANOKS_CATEGORY_UNITS,
        CHAKANOKS_CATEGORIES,
        getCategoryItems,
        getCategoryDefaultUnit,
        populateCategoryItems,
        searchItems
    };
}


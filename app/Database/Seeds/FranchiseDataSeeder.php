<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FranchiseDataSeeder extends Seeder
{
    public function run()
    {
        // Get existing branches (excluding central)
        $branches = $this->db->table('branches')
            ->where('code !=', 'CENTRAL')
            ->get()
            ->getResultArray();
        
        // Get existing products
        $products = $this->db->table('products')
            ->limit(10)
            ->get()
            ->getResultArray();
        
        // Get franchise manager user
        $franchiseManager = $this->db->table('users')
            ->where('role', 'franchisemanager')
            ->orWhere('role', 'franchise_manager')
            ->get()
            ->getRowArray();
        
        if (empty($branches) || empty($products)) {
            echo "Warning: Need existing branches and products to seed franchise data.\n";
            return;
        }
        
        $managerId = $franchiseManager['id'] ?? 1;
        
        // ============================================
        // SEED FRANCHISE APPLICATIONS
        // ============================================
        $applications = [
            [
                'applicant_name' => 'Juan Dela Cruz',
                'email' => 'juan.delacruz@email.com',
                'phone' => '+63 912 345 6789',
                'proposed_location' => 'SM City Cebu',
                'city' => 'Cebu City',
                'investment_capital' => 5000000.00,
                'business_experience' => 'Operated a retail store for 5 years. Experience in inventory management and customer service.',
                'status' => 'pending',
                'notes' => 'Strong candidate with good business background',
                'reviewed_by' => $managerId,
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
            ],
            [
                'applicant_name' => 'Roberto Tan',
                'email' => 'roberto.tan@email.com',
                'phone' => '+63 915 345 6789',
                'proposed_location' => 'SM Megamall',
                'city' => 'Mandaluyong',
                'investment_capital' => 10000000.00,
                'business_experience' => 'Owns 3 successful restaurants. Looking to expand into retail franchise.',
                'status' => 'approved',
                'notes' => 'Approved for franchise partnership',
                'reviewed_by' => $managerId,
                'reviewed_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'applicant_name' => 'Anna Garcia',
                'email' => 'anna.garcia@email.com',
                'phone' => '+63 918 456 7890',
                'proposed_location' => 'Robinsons Place Iloilo',
                'city' => 'Iloilo City',
                'investment_capital' => 4500000.00,
                'business_experience' => 'First-time entrepreneur with business management degree.',
                'status' => 'rejected',
                'notes' => 'Insufficient business experience',
                'reviewed_by' => $managerId,
                'reviewed_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
        ];
        
        $this->db->table('franchise_applications')->insertBatch($applications);
        echo "Seeded " . count($applications) . " franchise applications.\n";
        
        // ============================================
        // SEED SUPPLY ALLOCATIONS
        // ============================================
        if (!empty($branches) && !empty($products)) {
            $allocations = [];
            
            foreach (array_slice($branches, 0, 3) as $index => $branch) {
                $product = $products[array_rand($products)];
                
                $statuses = ['pending', 'approved', 'shipped', 'delivered'];
                $status = $statuses[$index % count($statuses)];
                
                $qty = rand(50, 200);
                $price = isset($product['price']) ? floatval($product['price']) : 150.00;
                
                $allocations[] = [
                    'branch_id' => $branch['id'],
                    'product_id' => $product['id'],
                    'allocated_qty' => $qty,
                    'unit_price' => $price,
                    'total_amount' => $qty * $price,
                    'status' => $status,
                    'allocation_date' => date('Y-m-d', strtotime('-' . (10 - $index * 2) . ' days')),
                    'delivery_date' => date('Y-m-d', strtotime('+' . (5 + $index * 2) . ' days')),
                    'notes' => 'Regular supply allocation for ' . $branch['name'],
                    'created_by' => $managerId,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-' . (10 - $index * 2) . ' days')),
                    'updated_at' => date('Y-m-d H:i:s', strtotime('-' . (10 - $index * 2) . ' days')),
                ];
            }
            
            if (!empty($allocations)) {
                $this->db->table('supply_allocations')->insertBatch($allocations);
                echo "Seeded " . count($allocations) . " supply allocations.\n";
            }
        }
        
        // ============================================
        // SEED ROYALTY PAYMENTS
        // ============================================
        if (!empty($branches)) {
            $royalties = [];
            
            foreach (array_slice($branches, 0, 4) as $index => $branch) {
                $grossSales = rand(500000, 2000000);
                $royaltyRate = 5.00;
                $royaltyAmount = ($grossSales * $royaltyRate) / 100;
                $marketingFee = rand(5000, 20000);
                $totalDue = $royaltyAmount + $marketingFee;
                
                // Vary payment status
                $statuses = ['pending', 'partial', 'paid', 'overdue'];
                $status = $statuses[$index % count($statuses)];
                
                $amountPaid = 0;
                $paidDate = null;
                
                if ($status === 'paid') {
                    $amountPaid = $totalDue;
                    $paidDate = date('Y-m-d', strtotime('-' . rand(1, 5) . ' days'));
                } elseif ($status === 'partial') {
                    $amountPaid = $totalDue * (rand(30, 70) / 100);
                    $paidDate = date('Y-m-d', strtotime('-' . rand(1, 3) . ' days'));
                }
                
                $balance = $totalDue - $amountPaid;
                
                $month = date('n') - $index;
                $year = date('Y');
                if ($month <= 0) {
                    $month += 12;
                    $year--;
                }
                
                $royalties[] = [
                    'branch_id' => $branch['id'],
                    'period_month' => $month,
                    'period_year' => $year,
                    'gross_sales' => $grossSales,
                    'royalty_rate' => $royaltyRate,
                    'royalty_amount' => $royaltyAmount,
                    'marketing_fee' => $marketingFee,
                    'total_due' => $totalDue,
                    'amount_paid' => $amountPaid,
                    'balance' => $balance,
                    'status' => $status,
                    'due_date' => date('Y-m-d', strtotime('first day of +' . (1 - $index) . ' month')),
                    'paid_date' => $paidDate,
                    'payment_reference' => $amountPaid > 0 ? 'REF-' . strtoupper(substr(md5($branch['id'] . $month), 0, 8)) : null,
                    'notes' => 'Monthly royalty payment for ' . date('F Y', mktime(0, 0, 0, $month, 1, $year)),
                    'created_at' => date('Y-m-d H:i:s', strtotime('-' . (30 - $index * 7) . ' days')),
                    'updated_at' => date('Y-m-d H:i:s', strtotime('-' . (30 - $index * 7) . ' days')),
                ];
            }
            
            if (!empty($royalties)) {
                $this->db->table('royalty_payments')->insertBatch($royalties);
                echo "Seeded " . count($royalties) . " royalty payment records.\n";
            }
        }
        
        echo "\n✅ Franchise data seeding completed successfully!\n";
    }
}


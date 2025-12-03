<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FranchiseExpansionSeeder extends Seeder
{
    public function run()
    {
        echo "\n🚀 Starting Franchise Expansion Data Seeding...\n\n";
        
        // Get franchise manager user
        $franchiseManager = $this->db->table('users')
            ->groupStart()
                ->where('role', 'franchisemanager')
                ->orWhere('role', 'franchise_manager')
            ->groupEnd()
            ->get()
            ->getRowArray();
        
        if (empty($franchiseManager)) {
            echo "⚠️  Warning: No franchise manager found. Using default user ID 1.\n";
            $managerId = 1;
        } else {
            $managerId = $franchiseManager['id'];
            echo "✅ Found franchise manager: " . $franchiseManager['email'] . " (ID: {$managerId})\n\n";
        }
        
        // ============================================
        // STEP 1: CREATE BRANCHES OUTSIDE DAVAO
        // ============================================
        echo "📍 Step 1: Creating branches outside Davao City...\n";
        
        $newBranches = [
            [
                'code' => 'CEBU01',
                'name' => 'SM City Cebu Branch',
                'address' => 'Juan Luna Avenue, North Reclamation Area, Cebu City, 6000',
                'franchise_owner_id' => null,
                'franchise_type' => 'franchised',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
            ],
            [
                'code' => 'MANILA01',
                'name' => 'SM Megamall Branch',
                'address' => 'EDSA corner Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong City, Metro Manila',
                'franchise_owner_id' => null,
                'franchise_type' => 'franchised',
                'created_at' => date('Y-m-d H:i:s', strtotime('-25 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-25 days')),
            ],
            [
                'code' => 'ILOILO01',
                'name' => 'Robinsons Place Iloilo Branch',
                'address' => 'Quezon Street corner Ledesma Street, Iloilo City Proper, Iloilo City, 5000',
                'franchise_owner_id' => null,
                'franchise_type' => 'franchised',
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
            ],
            [
                'code' => 'CDO01',
                'name' => 'Centrio Mall Branch',
                'address' => 'Claro M. Recto Avenue, Carmen, Cagayan de Oro City, 9000',
                'franchise_owner_id' => null,
                'franchise_type' => 'franchised',
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
            ],
            [
                'code' => 'BAGUIO01',
                'name' => 'Session Road Branch',
                'address' => 'Session Road, Baguio City, Benguet, 2600',
                'franchise_owner_id' => null,
                'franchise_type' => 'franchised',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
            ],
        ];
        
        $insertedBranches = [];
        foreach ($newBranches as $branch) {
            // Check if branch already exists
            $existing = $this->db->table('branches')
                ->where('code', $branch['code'])
                ->get()
                ->getRowArray();
            
            if ($existing) {
                echo "   ⚠️  Branch {$branch['code']} already exists, skipping...\n";
                $insertedBranches[] = $existing;
            } else {
                $this->db->table('branches')->insert($branch);
                $branchId = $this->db->insertID();
                $branch['id'] = $branchId;
                $insertedBranches[] = $branch;
                echo "   ✅ Created: {$branch['name']} ({$branch['code']})\n";
            }
        }
        
        echo "✅ Total branches created/verified: " . count($insertedBranches) . "\n\n";
        
        // ============================================
        // STEP 2: SEED FRANCHISE APPLICATIONS (Outside Davao)
        // ============================================
        echo "📝 Step 2: Creating franchise applications...\n";
        
        $applications = [
            // Cebu Applications
            [
                'applicant_name' => 'Maria Clara Santos',
                'email' => 'maria.santos@cebu-business.com',
                'phone' => '+63 917 234 5678',
                'proposed_location' => 'SM City Cebu, North Reclamation Area',
                'city' => 'Cebu City',
                'investment_capital' => 8500000.00,
                'business_experience' => 'MBA graduate with 5 years experience in retail management. Currently operates 2 successful convenience stores in Cebu.',
                'status' => 'approved',
                'notes' => 'Excellent qualifications. Strong local market knowledge. Approved for SM City Cebu location.',
                'reviewed_by' => $managerId,
                'reviewed_at' => date('Y-m-d H:i:s', strtotime('-28 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-32 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-28 days')),
            ],
            [
                'applicant_name' => 'Juan Carlos Reyes',
                'email' => 'jc.reyes@email.com',
                'phone' => '+63 918 345 6789',
                'proposed_location' => 'Ayala Center Cebu',
                'city' => 'Cebu City',
                'investment_capital' => 6000000.00,
                'business_experience' => 'First-time franchisee but has business degree and family retail background.',
                'status' => 'under_review',
                'notes' => 'Under evaluation. Scheduled for site visit next week.',
                'reviewed_by' => $managerId,
                'reviewed_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            
            // Manila Applications
            [
                'applicant_name' => 'Roberto Antonio Tan',
                'email' => 'roberto.tan@manila-ventures.com',
                'phone' => '+63 915 456 7890',
                'proposed_location' => 'SM Megamall, Ortigas Center',
                'city' => 'Mandaluyong City',
                'investment_capital' => 12000000.00,
                'business_experience' => 'Owns 3 successful restaurants in Metro Manila. 10+ years in food and retail industry. Strong financial backing.',
                'status' => 'approved',
                'notes' => 'Premium location approved. High-capacity franchisee with proven track record.',
                'reviewed_by' => $managerId,
                'reviewed_at' => date('Y-m-d H:i:s', strtotime('-23 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-27 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-23 days')),
            ],
            [
                'applicant_name' => 'Patricia Anne Cruz',
                'email' => 'patricia.cruz@email.com',
                'phone' => '+63 920 567 8901',
                'proposed_location' => 'Bonifacio Global City',
                'city' => 'Taguig City',
                'investment_capital' => 10000000.00,
                'business_experience' => 'Corporate executive with MBA. Looking to start own business. No prior retail experience.',
                'status' => 'pending',
                'notes' => 'Strong financial capacity but lacks retail experience. Considering acceptance with training program.',
                'reviewed_by' => $managerId,
                'created_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
            ],
            
            // Iloilo Applications
            [
                'applicant_name' => 'Ana Marie Garcia',
                'email' => 'ana.garcia@iloilo-retail.com',
                'phone' => '+63 919 678 9012',
                'proposed_location' => 'Robinsons Place Iloilo',
                'city' => 'Iloilo City',
                'investment_capital' => 5500000.00,
                'business_experience' => 'Family owns established retail business in Iloilo. 7 years in business management.',
                'status' => 'approved',
                'notes' => 'Local market expert. Family business background. Approved for Robinsons Place location.',
                'reviewed_by' => $managerId,
                'reviewed_at' => date('Y-m-d H:i:s', strtotime('-18 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-22 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-18 days')),
            ],
            [
                'applicant_name' => 'Michael James Lopez',
                'email' => 'mj.lopez@email.com',
                'phone' => '+63 921 789 0123',
                'proposed_location' => 'SM City Iloilo',
                'city' => 'Iloilo City',
                'investment_capital' => 4200000.00,
                'business_experience' => 'Recent business graduate. Limited practical experience.',
                'status' => 'rejected',
                'notes' => 'Insufficient experience and below minimum investment threshold for SM mall location.',
                'reviewed_by' => $managerId,
                'reviewed_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            
            // Cagayan de Oro Applications
            [
                'applicant_name' => 'Santiago Luis Ramos',
                'email' => 'santiago.ramos@cdo-enterprises.com',
                'phone' => '+63 922 890 1234',
                'proposed_location' => 'Centrio Mall, Claro M. Recto Avenue',
                'city' => 'Cagayan de Oro City',
                'investment_capital' => 7000000.00,
                'business_experience' => 'Successful businessman in Northern Mindanao. Owns hardware stores and construction supply business.',
                'status' => 'approved',
                'notes' => 'Strong business acumen. Excellent financial standing. Approved for Centrio Mall location.',
                'reviewed_by' => $managerId,
                'reviewed_at' => date('Y-m-d H:i:s', strtotime('-13 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-17 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-13 days')),
            ],
            
            // Baguio Applications
            [
                'applicant_name' => 'Catherine Rose Mendoza',
                'email' => 'catherine.mendoza@baguio-business.com',
                'phone' => '+63 923 901 2345',
                'proposed_location' => 'Session Road, Baguio City',
                'city' => 'Baguio City',
                'investment_capital' => 6500000.00,
                'business_experience' => '8 years operating a café and gift shop on Session Road. Strong tourist market knowledge.',
                'status' => 'approved',
                'notes' => 'Prime tourist location. Experienced local operator. Approved for Session Road location.',
                'reviewed_by' => $managerId,
                'reviewed_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
            ],
            [
                'applicant_name' => 'David Emmanuel Torres',
                'email' => 'david.torres@email.com',
                'phone' => '+63 924 012 3456',
                'proposed_location' => 'SM City Baguio',
                'city' => 'Baguio City',
                'investment_capital' => 5800000.00,
                'business_experience' => '4 years in retail sales. Currently store manager at another retail chain.',
                'status' => 'pending',
                'notes' => 'Good retail experience but needs to clarify financing sources.',
                'reviewed_by' => $managerId,
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
            ],
        ];
        
        $insertedCount = 0;
        foreach ($applications as $application) {
            try {
                $this->db->table('franchise_applications')->insert($application);
                $insertedCount++;
            } catch (\Exception $e) {
                echo "   ⚠️  Error inserting application for {$application['applicant_name']}: " . $e->getMessage() . "\n";
            }
        }
        echo "✅ Created {$insertedCount} franchise applications\n\n";
        
        // ============================================
        // STEP 3: SEED SUPPLY ALLOCATIONS
        // ============================================
        echo "📦 Step 3: Creating supply allocations...\n";
        
        // Get products for allocation
        $products = $this->db->table('products')
            ->limit(20)
            ->get()
            ->getResultArray();
        
        if (empty($products)) {
            echo "⚠️  Warning: No products found. Skipping supply allocations.\n\n";
        } else {
            $allocations = [];
            
            // Allocate to each new branch
            foreach ($insertedBranches as $index => $branch) {
                // 2-3 allocations per branch
                $numAllocations = rand(2, 3);
                
                for ($i = 0; $i < $numAllocations; $i++) {
                    $product = $products[array_rand($products)];
                    $qty = rand(100, 500);
                    $price = isset($product['price']) && $product['price'] > 0 ? floatval($product['price']) : 150.00;
                    
                    $statuses = ['pending', 'approved', 'shipped', 'delivered'];
                    $statusIndex = ($index + $i) % count($statuses);
                    $status = $statuses[$statusIndex];
                    
                    $daysAgo = 25 - ($index * 3) - $i;
                    $deliveryDays = 7 + ($i * 3);
                    
                    $allocations[] = [
                        'branch_id' => $branch['id'],
                        'product_id' => $product['id'],
                        'allocated_qty' => $qty,
                        'unit_price' => $price,
                        'total_amount' => $qty * $price,
                        'status' => $status,
                        'allocation_date' => date('Y-m-d', strtotime("-{$daysAgo} days")),
                        'delivery_date' => date('Y-m-d', strtotime("+{$deliveryDays} days")),
                        'notes' => "Initial supply allocation for {$branch['name']} - " . ($i + 1) . " of {$numAllocations}",
                        'created_by' => $managerId,
                        'created_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
                        'updated_at' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
                    ];
                }
            }
            
            $insertedAllocationCount = 0;
            foreach ($allocations as $allocation) {
                try {
                    $this->db->table('supply_allocations')->insert($allocation);
                    $insertedAllocationCount++;
                } catch (\Exception $e) {
                    echo "   ⚠️  Error inserting allocation: " . $e->getMessage() . "\n";
                }
            }
            echo "✅ Created {$insertedAllocationCount} supply allocations\n\n";
        }
        
        // ============================================
        // STEP 4: SEED ROYALTY PAYMENTS
        // ============================================
        echo "💰 Step 4: Creating royalty payment records...\n";
        
        $royalties = [];
        
        foreach ($insertedBranches as $index => $branch) {
            // Create 2-3 months of royalty records per branch
            $numMonths = rand(2, 3);
            
            for ($monthOffset = 0; $monthOffset < $numMonths; $monthOffset++) {
                $month = date('n') - $monthOffset - $index;
                $year = date('Y');
                
                if ($month <= 0) {
                    $month += 12;
                    $year--;
                }
                
                $grossSales = rand(800000, 3000000);
                $royaltyRate = 5.00;
                $royaltyAmount = ($grossSales * $royaltyRate) / 100;
                $marketingFee = rand(10000, 30000);
                $totalDue = $royaltyAmount + $marketingFee;
                
                // Vary payment status
                $statusOptions = ['pending', 'partial', 'paid', 'overdue'];
                $statusIndex = ($index + $monthOffset) % count($statusOptions);
                $status = $statusOptions[$statusIndex];
                
                $amountPaid = 0;
                $paidDate = null;
                
                if ($status === 'paid') {
                    $amountPaid = $totalDue;
                    $paidDate = date('Y-m-d', strtotime('-' . rand(1, 10) . ' days'));
                } elseif ($status === 'partial') {
                    $amountPaid = $totalDue * (rand(40, 80) / 100);
                    $paidDate = date('Y-m-d', strtotime('-' . rand(1, 5) . ' days'));
                }
                
                $balance = $totalDue - $amountPaid;
                
                $createdDaysAgo = 35 - ($index * 5) - ($monthOffset * 3);
                
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
                    'due_date' => date('Y-m-d', strtotime("first day of {$year}-{$month} +1 month")),
                    'paid_date' => $paidDate,
                    'payment_reference' => $amountPaid > 0 ? 'REF-' . strtoupper(substr(md5($branch['id'] . $month . $year), 0, 8)) : null,
                    'notes' => "Monthly royalty for {$branch['name']} - " . date('F Y', mktime(0, 0, 0, $month, 1, $year)),
                    'created_at' => date('Y-m-d H:i:s', strtotime("-{$createdDaysAgo} days")),
                    'updated_at' => date('Y-m-d H:i:s', strtotime("-{$createdDaysAgo} days")),
                ];
            }
        }
        
        $insertedRoyaltyCount = 0;
        foreach ($royalties as $royalty) {
            try {
                $this->db->table('royalty_payments')->insert($royalty);
                $insertedRoyaltyCount++;
            } catch (\Exception $e) {
                echo "   ⚠️  Error inserting royalty: " . $e->getMessage() . "\n";
            }
        }
        echo "✅ Created {$insertedRoyaltyCount} royalty payment records\n\n";
        
        // ============================================
        // SUMMARY
        // ============================================
        echo "═══════════════════════════════════════════════════════\n";
        echo "✅ Franchise Expansion Data Seeding Complete!\n";
        echo "═══════════════════════════════════════════════════════\n\n";
        
        echo "📊 Summary:\n";
        echo "   • Branches created: " . count($insertedBranches) . "\n";
        echo "   • Applications: " . ($insertedCount ?? 0) . "\n";
        echo "   • Supply Allocations: " . ($insertedAllocationCount ?? 0) . "\n";
        echo "   • Royalty Records: " . ($insertedRoyaltyCount ?? 0) . "\n\n";
        
        echo "🏢 New Branches:\n";
        foreach ($insertedBranches as $branch) {
            echo "   ✓ {$branch['name']} ({$branch['code']}) - " . explode(',', $branch['address'])[count(explode(',', $branch['address'])) - 1] . "\n";
        }
        
        echo "\n🎉 Your franchise network now extends beyond Davao!\n";
        echo "🌏 You can now manage branches in Cebu, Manila, Iloilo, CDO, and Baguio.\n\n";
    }
}


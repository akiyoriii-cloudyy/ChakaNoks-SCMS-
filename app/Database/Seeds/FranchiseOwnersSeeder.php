<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FranchiseOwnersSeeder extends Seeder
{
    public function run()
    {
        echo "\n👥 Seeding Franchise Owners Data...\n\n";
        
        // ============================================
        // CREATE FRANCHISE OWNERS
        // ============================================
        // These are the approved franchisees who will own the franchise branches
        
        $franchiseOwners = [
            // Owner 1 - Cebu Branch
            [
                'owner_name' => 'Maria Clara Santos',
                'email' => 'maria.santos@cebu-business.com',
                'phone' => '+63 917 234 5678',
                'address' => 'Unit 3B, Pacific Plaza Towers, Archbishop Reyes Avenue, Lahug, Cebu City, 6000',
                'tax_id' => '123-456-789-000',
                'business_license' => 'CEBU-BL-2025-001234',
                'status' => 'active',
                'joined_date' => date('Y-m-d', strtotime('-30 days')),
                'notes' => 'MBA graduate with 5 years retail management experience. Operates 2 successful convenience stores in Cebu. Approved for SM City Cebu franchise.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
            ],
            
            // Owner 2 - Manila Branch
            [
                'owner_name' => 'Roberto Antonio Tan',
                'email' => 'roberto.tan@manila-ventures.com',
                'phone' => '+63 915 456 7890',
                'address' => '25th Floor, Discovery Center, ADB Avenue, Ortigas Center, Pasig City, Metro Manila',
                'tax_id' => '234-567-890-000',
                'business_license' => 'MANILA-BL-2025-005678',
                'status' => 'active',
                'joined_date' => date('Y-m-d', strtotime('-25 days')),
                'notes' => 'Owns 3 successful restaurants in Metro Manila. 10+ years in food and retail industry. Strong financial backing. Premium franchisee for SM Megamall location.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-25 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-25 days')),
            ],
            
            // Owner 3 - Iloilo Branch
            [
                'owner_name' => 'Ana Marie Garcia',
                'email' => 'ana.garcia@iloilo-retail.com',
                'phone' => '+63 919 678 9012',
                'address' => 'Garcia Building, Quezon Street, Iloilo City Proper, Iloilo, 5000',
                'tax_id' => '345-678-901-000',
                'business_license' => 'ILOILO-BL-2025-002345',
                'status' => 'active',
                'joined_date' => date('Y-m-d', strtotime('-20 days')),
                'notes' => 'Family owns established retail business in Iloilo. 7 years in business management. Local market expert with deep community connections.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
            ],
            
            // Owner 4 - CDO Branch
            [
                'owner_name' => 'Santiago Luis Ramos',
                'email' => 'santiago.ramos@cdo-enterprises.com',
                'phone' => '+63 922 890 1234',
                'address' => 'Ramos Commercial Complex, Corrales Avenue, Cagayan de Oro City, 9000',
                'tax_id' => '456-789-012-000',
                'business_license' => 'CDO-BL-2025-003456',
                'status' => 'active',
                'joined_date' => date('Y-m-d', strtotime('-15 days')),
                'notes' => 'Successful businessman in Northern Mindanao. Owns hardware stores and construction supply business. Excellent financial standing and business acumen.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
            ],
            
            // Owner 5 - Baguio Branch
            [
                'owner_name' => 'Catherine Rose Mendoza',
                'email' => 'catherine.mendoza@baguio-business.com',
                'phone' => '+63 923 901 2345',
                'address' => 'Mendoza Building, Upper Session Road, Baguio City, Benguet, 2600',
                'tax_id' => '567-890-123-000',
                'business_license' => 'BAGUIO-BL-2025-004567',
                'status' => 'active',
                'joined_date' => date('Y-m-d', strtotime('-10 days')),
                'notes' => '8 years operating a café and gift shop on Session Road. Strong tourist market knowledge and established local presence.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
            ],
        ];
        
        // Insert franchise owners
        $insertedCount = 0;
        $insertedOwners = [];
        
        foreach ($franchiseOwners as $owner) {
            try {
                // Check if owner already exists
                $existing = $this->db->table('franchise_owners')
                    ->where('email', $owner['email'])
                    ->get()
                    ->getRowArray();
                
                if ($existing) {
                    echo "   ⚠️  Owner '{$owner['owner_name']}' already exists, skipping...\n";
                    $insertedOwners[] = $existing;
                } else {
                    $this->db->table('franchise_owners')->insert($owner);
                    $ownerId = $this->db->insertID();
                    $owner['id'] = $ownerId;
                    $insertedOwners[] = $owner;
                    $insertedCount++;
                    echo "   ✅ Created: {$owner['owner_name']} (ID: {$ownerId})\n";
                }
            } catch (\Exception $e) {
                echo "   ❌ Error inserting owner {$owner['owner_name']}: " . $e->getMessage() . "\n";
            }
        }
        
        echo "\n✅ Total franchise owners created/verified: {$insertedCount}\n\n";
        
        // ============================================
        // LINK FRANCHISE OWNERS TO BRANCHES
        // ============================================
        echo "🔗 Linking franchise owners to branches...\n\n";
        
        $branchOwnerMapping = [
            'CEBU01' => 0,    // Maria Clara Santos
            'MANILA01' => 1,  // Roberto Antonio Tan
            'ILOILO01' => 2,  // Ana Marie Garcia
            'CDO01' => 3,     // Santiago Luis Ramos
            'BAGUIO01' => 4,  // Catherine Rose Mendoza
        ];
        
        $linkedCount = 0;
        foreach ($branchOwnerMapping as $branchCode => $ownerIndex) {
            try {
                // Get branch
                $branch = $this->db->table('branches')
                    ->where('code', $branchCode)
                    ->get()
                    ->getRowArray();
                
                if (!$branch) {
                    echo "   ⚠️  Branch {$branchCode} not found, skipping...\n";
                    continue;
                }
                
                // Get owner
                if (!isset($insertedOwners[$ownerIndex])) {
                    echo "   ⚠️  Owner index {$ownerIndex} not found, skipping...\n";
                    continue;
                }
                
                $owner = $insertedOwners[$ownerIndex];
                
                // Update branch with owner_id and franchise_type
                $this->db->table('branches')
                    ->where('id', $branch['id'])
                    ->update([
                        'franchise_owner_id' => $owner['id'],
                        'franchise_type' => 'franchised',
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                
                $linkedCount++;
                echo "   ✅ Linked: {$owner['owner_name']} → {$branch['name']} ({$branchCode})\n";
                
            } catch (\Exception $e) {
                echo "   ❌ Error linking {$branchCode}: " . $e->getMessage() . "\n";
            }
        }
        
        echo "\n✅ Total branches linked: {$linkedCount}\n\n";
        
        // ============================================
        // SUMMARY
        // ============================================
        echo "═══════════════════════════════════════════════════════\n";
        echo "✅ Franchise Owners Data Seeding Complete!\n";
        echo "═══════════════════════════════════════════════════════\n\n";
        
        echo "📊 Summary:\n";
        echo "   • Franchise Owners Created: {$insertedCount}\n";
        echo "   • Branches Linked: {$linkedCount}\n\n";
        
        echo "👥 Franchise Owners:\n";
        foreach ($insertedOwners as $owner) {
            echo "   ✓ {$owner['owner_name']} - {$owner['email']}\n";
        }
        
        echo "\n🏢 Franchise Branches Owned:\n";
        
        $ownedBranches = $this->db->table('branches b')
            ->select('b.code, b.name, b.address, fo.owner_name')
            ->join('franchise_owners fo', 'fo.id = b.franchise_owner_id', 'left')
            ->where('b.franchise_type', 'franchised')
            ->where('b.franchise_owner_id IS NOT NULL')
            ->get()
            ->getResultArray();
        
        foreach ($ownedBranches as $branch) {
            echo "   ✓ {$branch['code']} - {$branch['name']} (Owner: {$branch['owner_name']})\n";
        }
        
        echo "\n🎉 Your franchise ownership structure is now complete!\n\n";
    }
}


<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

/**
 * This seeder creates delivery records for purchase orders that don't have them
 */
class CreateMissingDeliveriesSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now();
        
        echo "=== Creating Missing Deliveries ===\n\n";
        
        // Get all purchase orders
        $pos = $this->db->table('purchase_orders')
            ->get()
            ->getResultArray();
        
        echo "Total purchase orders: " . count($pos) . "\n\n";
        
        $createdCount = 0;
        
        foreach ($pos as $po) {
            // Check if delivery exists for this PO
            $deliveryExists = $this->db->table('deliveries')
                ->where('purchase_order_id', $po['id'])
                ->countAllResults();
            
            if ($deliveryExists === 0) {
                echo "Creating delivery for PO #{$po['id']} ({$po['order_number']})...\n";
                
                // Generate delivery number
                $deliveryNumber = 'DEL-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                
                // Ensure unique delivery number
                while ($this->db->table('deliveries')->where('delivery_number', $deliveryNumber)->countAllResults() > 0) {
                    $deliveryNumber = 'DEL-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                }
                
                // Create delivery
                $deliveryData = [
                    'delivery_number' => $deliveryNumber,
                    'purchase_order_id' => (int)$po['id'],
                    'supplier_id' => (int)$po['supplier_id'],
                    'branch_id' => (int)$po['branch_id'],
                    'scheduled_by' => $po['approved_by'] ?? null,
                    'scheduled_date' => $po['expected_delivery_date'] ?? date('Y-m-d', strtotime('+3 days')),
                    'status' => 'scheduled',
                    'notes' => 'Auto-created from Purchase Order (seeder)',
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                
                $inserted = $this->db->table('deliveries')->insert($deliveryData);
                
                if ($inserted) {
                    $deliveryId = $this->db->insertID();
                    echo "  ✅ Created delivery #{$deliveryId} ({$deliveryNumber})\n";
                    
                    // Create delivery items from PO items
                    $poItems = $this->db->table('purchase_order_items')
                        ->where('purchase_order_id', $po['id'])
                        ->get()
                        ->getResultArray();
                    
                    foreach ($poItems as $poItem) {
                        $this->db->table('delivery_items')->insert([
                            'delivery_id' => $deliveryId,
                            'product_id' => $poItem['product_id'],
                            'expected_quantity' => $poItem['quantity'],
                            'received_quantity' => 0,
                            'condition_status' => 'good',
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                    
                    echo "  ✅ Created " . count($poItems) . " delivery items\n";
                    $createdCount++;
                } else {
                    $error = $this->db->error();
                    echo "  ❌ Failed to create delivery: " . json_encode($error) . "\n";
                }
            } else {
                echo "PO #{$po['id']} already has delivery\n";
            }
        }
        
        echo "\n✅ Created {$createdCount} new deliveries\n";
        
        // Verify
        echo "\n=== Verification ===\n";
        $totalDeliveries = $this->db->table('deliveries')->countAllResults();
        echo "Total deliveries: {$totalDeliveries}\n";
        
        $deliveries = $this->db->table('deliveries')
            ->select('deliveries.*, purchase_orders.order_number, branches.name as branch_name, suppliers.name as supplier_name')
            ->join('purchase_orders', 'purchase_orders.id = deliveries.purchase_order_id', 'left')
            ->join('branches', 'branches.id = deliveries.branch_id', 'left')
            ->join('suppliers', 'suppliers.id = deliveries.supplier_id', 'left')
            ->get()
            ->getResultArray();
        
        foreach ($deliveries as $d) {
            echo "\nDelivery #{$d['id']} ({$d['delivery_number']}):\n";
            echo "  PO: {$d['order_number']}\n";
            echo "  Branch: {$d['branch_name']}\n";
            echo "  Supplier: {$d['supplier_name']}\n";
            echo "  Status: {$d['status']}\n";
            echo "  Scheduled: {$d['scheduled_date']}\n";
        }
        
        echo "\n✅ Done!\n";
    }
}


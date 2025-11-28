<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * This seeder checks deliveries and purchase orders
 */
class CheckDeliveriesSeeder extends Seeder
{
    public function run()
    {
        echo "=== Checking Deliveries ===\n\n";
        
        // Get all deliveries
        $deliveries = $this->db->table('deliveries')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
        
        echo "Total deliveries: " . count($deliveries) . "\n\n";
        
        foreach ($deliveries as $d) {
            echo "Delivery #{$d['id']} - PO: {$d['purchase_order_id']}, Status: {$d['status']}\n";
        }
        
        echo "\n=== Checking Purchase Orders ===\n\n";
        
        // Get all purchase orders
        $pos = $this->db->table('purchase_orders')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
        
        echo "Total purchase orders: " . count($pos) . "\n\n";
        
        foreach ($pos as $po) {
            // Check if delivery exists for this PO
            $deliveryExists = $this->db->table('deliveries')
                ->where('purchase_order_id', $po['id'])
                ->countAllResults();
            
            $poNumber = $po['po_number'] ?? $po['order_number'] ?? 'N/A';
            echo "PO #{$po['id']} ({$poNumber}): Status={$po['status']}, Has Delivery=" . ($deliveryExists > 0 ? 'YES' : 'NO') . "\n";
        }
        
        // Check for POs without deliveries
        echo "\n=== POs Without Deliveries ===\n";
        $posWithoutDelivery = [];
        foreach ($pos as $po) {
            $deliveryExists = $this->db->table('deliveries')
                ->where('purchase_order_id', $po['id'])
                ->countAllResults();
            
            if ($deliveryExists === 0) {
                $posWithoutDelivery[] = $po;
            }
        }
        
        echo "POs without deliveries: " . count($posWithoutDelivery) . "\n";
        foreach ($posWithoutDelivery as $po) {
            $poNumber = $po['po_number'] ?? $po['order_number'] ?? 'N/A';
            echo "  - PO #{$po['id']} ({$poNumber}): Status={$po['status']}\n";
        }
        
        echo "\n✅ Check complete!\n";
    }
}


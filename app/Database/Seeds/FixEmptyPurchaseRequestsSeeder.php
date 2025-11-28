<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

/**
 * This seeder fixes purchase requests that have no items
 * by deleting them so users can create new ones
 */
class FixEmptyPurchaseRequestsSeeder extends Seeder
{
    public function run()
    {
        echo "=== Fixing Empty Purchase Requests ===\n\n";
        
        // Get all purchase requests
        $requests = $this->db->table('purchase_requests')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
        
        $deletedCount = 0;
        $fixedCount = 0;
        
        foreach ($requests as $request) {
            $itemCount = $this->db->table('purchase_request_items')
                ->where('purchase_request_id', $request['id'])
                ->countAllResults();
            
            if ($itemCount === 0) {
                // If status is pending or approved (not converted_to_po), delete it
                if (in_array($request['status'], ['pending', 'approved'])) {
                    echo "Deleting empty request {$request['request_number']} (ID: {$request['id']}, Status: {$request['status']})...\n";
                    
                    $this->db->table('purchase_requests')
                        ->where('id', $request['id'])
                        ->delete();
                    
                    $deletedCount++;
                } else {
                    echo "WARNING: Request {$request['request_number']} has no items but status is {$request['status']} - skipping\n";
                }
            }
        }
        
        echo "\n✅ Deleted {$deletedCount} empty purchase requests\n";
        
        // Now verify remaining requests
        echo "\n=== Remaining Purchase Requests ===\n";
        $remainingRequests = $this->db->table('purchase_requests')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
        
        foreach ($remainingRequests as $request) {
            $itemCount = $this->db->table('purchase_request_items')
                ->where('purchase_request_id', $request['id'])
                ->countAllResults();
            
            echo "PR #{$request['id']} ({$request['request_number']}): {$itemCount} items, Status: {$request['status']}\n";
        }
        
        echo "\n✅ Fix complete!\n";
    }
}


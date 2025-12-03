<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizeAccountsPayableTable extends Migration
{
    public function up()
    {
        // Step 1: Check if old columns exist and backup payment data
        $fields = $this->db->getFieldNames('accounts_payable');
        $payments = [];
        
        if (in_array('payment_date', $fields) && in_array('paid_amount', $fields)) {
            $payments = $this->db->query("
                SELECT id, purchase_order_id, payment_date, payment_method, 
                       payment_reference, paid_amount, notes
                FROM accounts_payable
                WHERE payment_date IS NOT NULL AND paid_amount > 0
            ")->getResultArray();
        }

        // Step 2: Modify accounts_payable table to remove redundant fields (only if they exist)
        if (in_array('payment_date', $fields)) {
            $this->db->query("
                ALTER TABLE accounts_payable
                DROP FOREIGN KEY IF EXISTS fk_accounts_payable_supplier,
                DROP FOREIGN KEY IF EXISTS fk_accounts_payable_branch,
                DROP COLUMN IF EXISTS supplier_id,
                DROP COLUMN IF EXISTS branch_id,
                DROP COLUMN IF EXISTS payment_date,
                DROP COLUMN IF EXISTS payment_method,
                DROP COLUMN IF EXISTS payment_reference,
                DROP COLUMN IF EXISTS paid_amount,
                DROP COLUMN IF EXISTS balance
            ");
        }

        // Step 3: Add columns (balance as computed, payment_status as regular) - only if they don't exist
        $fieldsAfter = $this->db->getFieldNames('accounts_payable');
        
        // Add amount_paid if it doesn't exist
        if (!in_array('amount_paid', $fieldsAfter)) {
            $this->db->query("
                ALTER TABLE accounts_payable
                ADD COLUMN amount_paid DECIMAL(10,2) DEFAULT 0.00 AFTER amount
            ");
        }
        
        // Refresh field list
        $fieldsAfter = $this->db->getFieldNames('accounts_payable');
        
        // Add balance as computed column if it doesn't exist
        if (!in_array('balance', $fieldsAfter)) {
            $this->db->query("
                ALTER TABLE accounts_payable
                ADD COLUMN balance DECIMAL(10,2) 
                    AS (amount - COALESCE(amount_paid, 0)) STORED
            ");
        }
        
        // Refresh field list again
        $fieldsAfter = $this->db->getFieldNames('accounts_payable');
        
        // Add payment_status if it doesn't exist
        if (!in_array('payment_status', $fieldsAfter)) {
            $this->db->query("
                ALTER TABLE accounts_payable
                ADD COLUMN payment_status ENUM('unpaid', 'partial', 'paid', 'overdue') 
                    DEFAULT 'unpaid' AFTER balance
            ");
        }

        // Step 4: Migrate payment data to payment_transactions table (only if we had old data)
        if (!empty($payments)) {
            foreach ($payments as $payment) {
                if ($payment['paid_amount'] > 0) {
                    $paymentNumber = 'PAY-' . date('Ymd') . '-' . str_pad($payment['id'], 4, '0', STR_PAD_LEFT);
                    
                    $this->db->table('payment_transactions')->insert([
                        'payment_number' => $paymentNumber,
                        'accounts_payable_id' => $payment['id'],
                        'payment_date' => $payment['payment_date'],
                        'payment_amount' => $payment['paid_amount'],
                        'payment_method' => $payment['payment_method'] ?? 'cash',
                        'payment_reference' => $payment['payment_reference'],
                        'notes' => 'Migrated from old accounts_payable structure',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    // Update amount_paid and payment_status in accounts_payable
                    $this->db->query("
                        UPDATE accounts_payable
                        SET amount_paid = {$payment['paid_amount']},
                            payment_status = 'paid'
                        WHERE id = {$payment['id']}
                    ");
                }
            }
            
            log_message('info', 'Migrated ' . count($payments) . ' payment records to payment_transactions');
        } else {
            log_message('info', 'No payment data to migrate (columns may already be normalized)');
        }

        // Step 5: Update payment_status for all records based on current state
        $this->updatePaymentStatuses();
    }

    public function down()
    {
        // Restore old structure
        $this->db->query("
            ALTER TABLE accounts_payable
            ADD COLUMN supplier_id INT(10) UNSIGNED,
            ADD COLUMN branch_id INT(10) UNSIGNED,
            ADD COLUMN payment_date DATE,
            ADD COLUMN payment_method VARCHAR(50),
            ADD COLUMN payment_reference VARCHAR(100),
            ADD COLUMN paid_amount DECIMAL(10,2) DEFAULT 0.00,
            DROP COLUMN IF EXISTS balance,
            DROP COLUMN IF EXISTS amount_paid,
            DROP COLUMN IF EXISTS payment_status
        ");

        // Restore data from payment_transactions
        $payments = $this->db->query("
            SELECT * FROM payment_transactions
            WHERE notes LIKE '%Migrated from old accounts_payable%'
        ")->getResultArray();

        foreach ($payments as $payment) {
            $this->db->query("
                UPDATE accounts_payable
                SET 
                    payment_date = '{$payment['payment_date']}',
                    payment_method = '{$payment['payment_method']}',
                    payment_reference = '{$payment['payment_reference']}',
                    paid_amount = {$payment['payment_amount']}
                WHERE id = {$payment['accounts_payable_id']}
            ");
        }

        // Restore foreign keys
        $this->db->query("
            ALTER TABLE accounts_payable
            ADD CONSTRAINT fk_accounts_payable_supplier 
                FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE,
            ADD CONSTRAINT fk_accounts_payable_branch 
                FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE
        ");
    }

    /**
     * Update payment_status for all accounts payable records
     */
    private function updatePaymentStatuses()
    {
        // Set status based on payment amounts
        $this->db->query("
            UPDATE accounts_payable
            SET payment_status = CASE 
                WHEN amount_paid >= amount THEN 'paid'
                WHEN amount_paid > 0 THEN 'partial'
                WHEN due_date < CURDATE() THEN 'overdue'
                ELSE 'unpaid'
            END
        ");
    }
}


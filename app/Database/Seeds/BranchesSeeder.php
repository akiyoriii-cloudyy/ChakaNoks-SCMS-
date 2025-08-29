<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class BranchesSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now();

        $rows = [
            ['code' => 'CENTRAL',  'name' => 'Central Office',  'created_at' => $now, 'updated_at' => $now],
            ['code' => 'MATINA',   'name' => 'Matina Branch',   'created_at' => $now, 'updated_at' => $now],
            ['code' => 'TORIL',    'name' => 'Toril Branch',    'created_at' => $now, 'updated_at' => $now],
            ['code' => 'BUHANGIN', 'name' => 'Buhangin Branch', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'AGDAO',    'name' => 'Agdao Branch',    'created_at' => $now, 'updated_at' => $now],
            ['code' => 'LANANG',   'name' => 'Lanang Branch',   'created_at' => $now, 'updated_at' => $now],
        ];

        $tbl = $this->db->table('branches');

        foreach ($rows as $r) {
            $existing = $tbl->where('code', $r['code'])->get()->getRowArray();

            if ($existing) {
                $tbl->where('id', $existing['id'])->update($r);
                echo "🔄 Updated branch: {$r['code']} ({$r['name']})\n";
            } else {
                $tbl->insert($r);
                echo "✅ Inserted branch: {$r['code']} ({$r['name']})\n";
            }
        }
    }
}

<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name','category','unit','quantity',
        'min_stock','max_stock','branch_id',
        'created_at','updated_at'
    ];
    protected $useTimestamps = true;

    public function getInventory(array $filters = []): array
    {
        $qb = $this->db->table($this->table . ' p')
            ->select('p.*, b.name AS branch_name')
            ->join('branches b', 'b.id = p.branch_id', 'left');

        if (!empty($filters['search'])) {
            $qb->groupStart()
               ->like('p.name', $filters['search'])
               ->orLike('p.category', $filters['search'])
               ->groupEnd();
        }
        if (!empty($filters['branch_id'])) {
            $qb->where('p.branch_id', $filters['branch_id']);
        }
        if (!empty($filters['date'])) {           // YYYY-MM-DD
            $qb->where('DATE(p.updated_at)', $filters['date']);
        }

        $rows = $qb->orderBy('p.updated_at', 'DESC')->get()->getResultArray();

        // compute status per item
        foreach ($rows as &$r) {
            $r['status'] = $this->computeStatus(
                (float)$r['quantity'],
                (float)$r['min_stock'],
                (float)$r['max_stock']
            );
        }

        // optional status filter (after computing)
        if (!empty($filters['status'])) {
            $want = strtolower($filters['status']);
            $rows = array_values(array_filter($rows, fn($x) => strtolower($x['status']) === $want));
        }

        return $rows;
    }

    public function countsByStatus(array $filters = []): array
    {
        $rows = $this->getInventory($filters);
        $counts = ['critical' => 0, 'low' => 0, 'good' => 0, 'total' => 0];

        foreach ($rows as $r) {
            if ($r['status'] === 'Critical')      $counts['critical']++;
            elseif ($r['status'] === 'Low Stock') $counts['low']++;
            else                                   $counts['good']++;
            $counts['total']++;
        }
        return $counts;
    }

    private function computeStatus(float $qty, float $min, float $max): string
    {
        if ($qty <= $min) return 'Critical';
        // “Low” = above min but within ~30% of the min→max band
        $threshold = $min + ($max - $min) * 0.30;
        if ($qty < $threshold) return 'Low Stock';
        return 'Good';
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Staff extends BaseController
{
    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        $session = session();

        // Role guard
        if (! $session->get('logged_in') || ! in_array($session->get('role'), ['inventorystaff','inventory_staff'])) {
            return redirect()->to('/auth/login');
        }

        // Demo data (replace with DB later)
        $items = [
            [
                'id'=>1,'name'=>'Chicken Wings','category'=>'Poultry','branch'=>'LANANG',
                'stock_qty'=>75,'unit'=>'kg','min'=>100,'max'=>300,'status'=>'Critical',
                'supplier'=>'Poultry Plus','updated_ago'=>'2 days ago','date'=>'2025-08-19','expiry'=>'2025-09-02'
            ],
            [
                'id'=>2,'name'=>'Chicken Breast','category'=>'Poultry','branch'=>'MATINA',
                'stock_qty'=>245,'unit'=>'kg','min'=>150,'max'=>400,'status'=>'Good',
                'supplier'=>'Quality Meats','updated_ago'=>'3 hours ago','date'=>'2025-08-21','expiry'=>'2025-09-05'
            ],
            [
                'id'=>3,'name'=>'Cooking Oil','category'=>'Cooking Supplies','branch'=>'TORIL',
                'stock_qty'=>120,'unit'=>'liters','min'=>80,'max'=>300,'status'=>'Low Stock',
                'supplier'=>'Oil Masters','updated_ago'=>'30 mins. ago','date'=>'2025-08-21','expiry'=>'2026-01-15'
            ],
            [
                'id'=>4,'name'=>'Seasoning Mix','category'=>'Condiments','branch'=>'BUHANGIN',
                'stock_qty'=>10,'unit'=>'packs','min'=>50,'max'=>200,'status'=>'Critical',
                'supplier'=>'Spice World','updated_ago'=>'1 hour ago','date'=>'2025-08-21','expiry'=>'2025-09-10'
            ],
        ];

        return view('dashboards/staff', [
            'items' => $items,
            'me'    => [
                'email' => $session->get('email'),
                'role'  => $session->get('role'),
            ],
        ]);
    }

    public function item($id)
    {
        // Later: fetch a single item by id
        return redirect()->to('/staff/dashboard');
    }
}

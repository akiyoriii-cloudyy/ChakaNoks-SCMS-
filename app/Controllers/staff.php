<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Staff extends BaseController
{
    public function index()
    {
        return $this->dashboard(); // /staff maps here automatically
    }

    public function dashboard()
    {
        $session = session();

        // Guard
        if (! $session->get('logged_in') || ! in_array($session->get('role'), ['inventorystaff','inventory_staff'])) {
            return redirect()->to('/auth/login');
        }

        // Demo data — replace with DB later
        $items = [
            [
                'id'=>1,'name'=>'Chicken Wings','category'=>'Poultry','branch'=>'Lanang Branch',
                'stock'=>'75 kg','min'=>100,'max'=>300,'status'=>'Critical',
                'supplier'=>'Poultry Plus','updated_ago'=>'2 days ago','date'=>'2025-08-19'
            ],
            [
                'id'=>2,'name'=>'Chicken Breast','category'=>'Poultry','branch'=>'Matina Branch',
                'stock'=>'245 kg','min'=>150,'max'=>400,'status'=>'Good',
                'supplier'=>'Quality Meats','updated_ago'=>'3 hours ago','date'=>'2025-08-21'
            ],
            [
                'id'=>3,'name'=>'Cooking Oil','category'=>'Cooking Supplies','branch'=>'Toril Branch',
                'stock'=>'120 liters','min'=>80,'max'=>300,'status'=>'Low Stock',
                'supplier'=>'Oil Masters','updated_ago'=>'30 mins. ago','date'=>'2025-08-21'
            ],
            [
                'id'=>4,'name'=>'Seasoning Mix','category'=>'Condiments','branch'=>'Buhangin Branch',
                'stock'=>'10 packets','min'=>50,'max'=>200,'status'=>'Critical',
                'supplier'=>'Spice World','updated_ago'=>'1 hour ago','date'=>'2025-08-21'
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

    // optional: /staff/item/12
    public function item($id)
    {
        // later: fetch by $id, for now just go back
        return redirect()->to('/staff/dashboard');
    }
}

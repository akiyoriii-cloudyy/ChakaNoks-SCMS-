<?php
namespace App\Controllers;

class Superadmin extends BaseController
{
    public function dashboard()
    {
        return view('dashboards/superadmin');
    }
}

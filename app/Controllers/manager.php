<?php
namespace App\Controllers;

class Manager extends BaseController
{
    public function dashboard()
    {
        return view('dashboards/manager');
    }
}

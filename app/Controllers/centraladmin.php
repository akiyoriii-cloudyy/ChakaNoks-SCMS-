<?php
namespace App\Controllers;

class CentralAdmin extends BaseController
{
    public function dashboard()
    {
        return view('dashboards/centraladmin');
    }
}

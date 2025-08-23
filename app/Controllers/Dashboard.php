<?php
namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('dashboard', [
            'email' => session()->get('email'),
            'role'  => session()->get('role'),
        ]);
    }
}

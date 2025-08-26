<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    // -------------------- LOGIN --------------------
    public function login()
    {
        return view('auth/login');
    }

    public function checkLogin()
    {
        $session   = session();
        $userModel = new UserModel();

        $email    = trim($this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        // ✅ Validate input
        if ($email === '' || $password === '') {
            $session->setFlashdata('error', 'Please enter email and password.');
            return redirect()->to('/auth/login')->withInput();
        }

        // ✅ Find user
        $user = $userModel->where('email', $email)->first();

        if (! $user) {
            $session->setFlashdata('error', 'User not found.');
            return redirect()->to('/auth/login')->withInput();
        }

        // ✅ Verify password
        if (! password_verify($password, $user['password'])) {
            $session->setFlashdata('error', 'Wrong password.');
            return redirect()->to('/auth/login')->withInput();
        }

        // ✅ Store session
        $session->set([
            'user_id'   => $user['id'],
            'email'     => $user['email'],
            'role'      => strtolower($user['role']),  // normalize role
            'branch_id' => $user['branch_id'] ?? null,
            'logged_in' => true,
        ]);

        // ✅ ROLE-BASED REDIRECT
$role = strtolower($session->get('role'));

switch ($role) {
    case 'superadmin':
        return redirect()->to('/superadmin/dashboard');
    case 'central_admin':
    case 'centraladmin':
        return redirect()->to('/centraladmin/dashboard');
    case 'branch_manager':
    case 'manager':
    case 'branchmanager':
        return redirect()->to('/manager/dashboard');
    case 'franchisemanager':
    case 'franchise_manager':
        return redirect()->to('/franchisemanager/dashboard');
    case 'logisticscoordinator':
    case 'logistics_coordinator':
        return redirect()->to('/logisticscoordinator/dashboard');
    case 'staff':
    case 'inventory_staff':
        return redirect()->to('/staff/dashboard');
    default:
        return redirect()->to('/dashboard'); // fallback
}

    }

    // -------------------- GENERIC DASHBOARD (fallback) --------------------
    public function dashboard()
    {
        $session = session();
        if (! $session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $db = \Config\Database::connect();

        $data = [
            'me' => [
                'email'     => $session->get('email'),
                'role'      => $session->get('role'),
                'branch_id' => $session->get('branch_id'),
            ],
            'userCount'    => $db->table('users')->countAllResults(),
            'branchCount'  => $db->table('branches')->countAllResults(),
            'productCount' => $db->table('products')->countAllResults(),
        ];

        return view('auth/dashboard', $data);
    }

    // -------------------- LOGOUT --------------------
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }

    // -------------------- FORGOT PASSWORD --------------------
   public function forgotPasswordForm()
    {
    return view('auth/forgotPasswordForm'); 
    }

    public function forgotPassword()
    {
        $email = trim($this->request->getPost('email'));

        if ($email === '') {
            return redirect()->back()->with('error', 'Please enter your email.');
        }

        return redirect()->to('/auth/login')
            ->with('success', 'If that email exists, a reset link has been sent.');
    }

         public function sendResetLink()
    {
        $email = $this->request->getPost('email');

            // TODO: validate if email exists in database
        // For now, just simulate success
        return redirect()->to('/auth/login')->with('success', 'Reset link sent to your email!');
    }

}

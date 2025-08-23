<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // Show login form
        return view('auth/login');
    }

    public function checkLogin()
    {
        $session = session();
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Find user by username
        $user = $userModel->where('username', $username)->first();

        if ($user) {
            // Verify password (assuming hashed with password_hash)
            if (password_verify($password, $user['password'])) {
                $session->set([
                    'user_id'   => $user['id'],
                    'username'  => $user['username'],
                    'logged_in' => true
                ]);

                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('error', 'Wrong password');
                return redirect()->to('/auth/login');
            }
        } else {
            $session->setFlashdata('error', 'User not found');
            return redirect()->to('/auth/login');
        }
    }

    public function dashboard()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        return view('dashboard');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/auth/login');
    }
}

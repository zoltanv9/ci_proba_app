<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function __construct()
    {
        helper('url');
    }

    public function index(): string
    {
        return view('login/index');
    }

    public function login()
    {
        $data = $this->request->getJSON(true);
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (empty($username) || empty($password)) {
            return $this->response->setJSON([
                'success'    => false,
                'message'    => 'Felhasználónév és jelszó szükséges.',
                'csrf_token' => csrf_hash(),
            ])->setStatusCode(400);
        }

        $db   = \Config\Database::connect();
        $user = $db->table('users')
            ->where('username', $username)
            ->get()
            ->getRowArray();

        if ($user === null || !password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'success'    => false,
                'message'    => 'Helytelen felhasználónév vagy jelszó.',
                'csrf_token' => csrf_hash(),
            ])->setStatusCode(401);
        }

        session()->set([
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'logged_in' => true,
        ]);

        session()->setFlashdata('success', 'Sikeres bejelentkezés');

        return $this->response->setJSON([
            'success'    => true,
            'message'    => 'Sikeresen bejelentkeztél, ' . $user['username'],
            'redirect'   => base_url('/'),
            'csrf_token' => csrf_hash(),
        ])->setStatusCode(200);
    }

    public function logout()
    {
        session()->destroy();
        return $this->response->setJSON([
            'success'    => true,
            'redirect'   => base_url('login'),
            'csrf_token' => csrf_hash(),
        ])->setStatusCode(200);
    }
}

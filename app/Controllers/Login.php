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

    public function attempt()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        $json = $this->request->getJSON(true);
        $username = $this->request->getPost('username') ?? (is_array($json) ? ($json['username'] ?? null) : null);
        $password = $this->request->getPost('password') ?? (is_array($json) ? ($json['password'] ?? null) : null);

        if ($username === null || $password === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Felhasználónév és jelszó szükséges.',
            ])->setStatusCode(400);
        }

        $validation = \Config\Services::validation()->setRules($rules);
        if (! $validation->run(['username' => $username, 'password' => $password])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(' ', $validation->getErrors()),
            ])->setStatusCode(400);
        }

        $db   = \Config\Database::connect();
        $user = $db->table('users')
            ->where('username', $username)
            ->get()
            ->getRowArray();

        if ($user === null || ! password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Helytelen felhasználónév vagy jelszó.',
            ])->setStatusCode(401);
        }

        session()->set([
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'logged_in' => true,
        ]);

        return $this->response->setJSON([
            'success'  => true,
            'message'   => 'Sikeresen bejelentkeztél, ' . $user['username'],
            'redirect'  => base_url('/'),
        ])->setStatusCode(200);
    }


    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('message', 'Sikeresen kijelentkeztél.');
    }
}

<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $username = env('ADMIN_DEFAULT_USERNAME', 'admin');
        $password = env('ADMIN_DEFAULT_PASSWORD', '');

        if ($password === '') {
            return;
        }

        $this->db->table('users')->insert([
            'username'   => $username,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

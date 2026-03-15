<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('menu')->insert([
            'label'      => 'Menü szerkesztő',
            'parent'     => null,
            'icon'       => 'bi bi-pencil-square',
            'url'        => '/',
            'created_at' => $now,
        ]);
        $this->db->table('menu')->insert([
            'label'      => 'Események keresése',
            'parent'     => null,
            'icon'       => 'bi bi-search',
            'url'        => '/esemenyek',
            'created_at' => $now,
        ]);
        $esemenyekId = $this->db->insertID();
        $this->db->table('menu')->insert([
            'label'      => 'Helyszínek',
            'parent'     => null,
            'icon'       => 'bi bi-geo-alt-fill',
            'url'        => '/helyszinek',
            'created_at' => $now,
        ]);
        $helyszinekId = $this->db->insertID();

        $this->db->table('menu')->insert([
            'label'      => 'Beállítások',
            'parent'     => null,
            'icon'       => 'bi bi-gear-fill',
            'url'        => '/beallitasok',
            'created_at' => $now,
        ]);
        $settingsId = $this->db->insertID();

        $this->db->table('menu')->insert([
            'label'      => 'Koncertek és fesztiválok',
            'parent'     => $esemenyekId,
            'icon'       => 'bi bi-music-note-beamed',
            'url'        => '/esemenyek/koncertek-fesztivalok',
            'created_at' => $now,
        ]);

        $this->db->table('menu')->insert([
            'label'      => 'Budapesti helyszínek',
            'parent'     => $helyszinekId,
            'icon'       => 'bi bi-geo-alt-fill',
            'url'        => '/helyszinek/budapesti-helyszinek',
            'created_at' => $now,
        ]);

        $this->db->table('menu')->insert([
            'label'      => 'Vidéki helyszínek',
            'parent'     => $helyszinekId,
            'icon'       => 'bi bi-geo-alt-fill',
            'url'        => '/helyszinek/videki-helyszinek',
            'created_at' => $now,
        ]);

        $this->db->table('menu')->insert([
            'label'      => 'Számlázási adatok',
            'parent'     => $settingsId,
            'icon'       => 'bi bi-cash-coin',
            'url'        => '/beallitasok/szamlazasi-adatok',
            'created_at' => $now,
        ]);
    }
}

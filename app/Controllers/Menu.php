<?php

namespace App\Controllers;

class Menu extends BaseController
{
    public function index(): string
    {
        return view('/menu/index', [
            'flashSuccess' => session()->getFlashdata('success')
        ]);
    }

    public function list()
    {
        $db = \Config\Database::connect();
        $menu = $db->table('menu')->get()->getResultArray();

        $orderedMenu = $this->buildMenuTree($menu);
        return $this->response->setJSON($orderedMenu);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $data = $this->request->getJSON(true);
        $label = $data['label'] ?? null;
        $parent = $data['parent'] ?? null;
        

        if (empty($label)) {
            return $this->response->setJSON([
                'success'    => false,
                'message'    => 'Menü cím szükséges.',
                'csrf_token' => csrf_hash(),
            ])->setStatusCode(400);
        }

        if (!empty($parent)) {
            $childMenus = $db->table('menu')->where('parent', $parent)->get()->getResultArray();
            foreach ($childMenus as $childMenu) {
                if ($childMenu['label'] === $label) {
                    return $this->response->setJSON([
                        'success'    => false,
                        'message'    => 'Ez a menü cím már létezik ennél a szülőnél.',
                        'csrf_token' => csrf_hash(),
                    ])->setStatusCode(400);
                }
            }
        }

        $payload = $this->request->getJSON(true);
        $insert = [
            'label'      => $payload['label'] ?? '',
            'parent'     => !empty($payload['parent']) ? (int) $payload['parent'] : null,
            'icon'       => !empty($payload['icon']) ? $payload['icon'] : null,
            'url'        => isset($payload['url']) && $payload['url'] !== '' ? $payload['url'] : null,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $db->table('menu')->insert($insert);
        return $this->response->setJSON([
            'success'    => true,
            'message'    => 'Menü sikeresen létrehozva.',
            'csrf_token' => csrf_hash(),
        ])->setStatusCode(201);
    }

    private function buildMenuTree(array $menu, $parentId = null) {
        $tree = [];

        foreach ($menu as $item) {
            $itemParent = $item['parent'] ?? null;
            $isRootItem = $itemParent === null;
            $matchesParent = ($parentId === null && $isRootItem) || ($itemParent == $parentId);

            if ($matchesParent) {
                $children = $this->buildMenuTree($menu, $item['id']);
                if ($children) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }

        return $tree;
    }
}

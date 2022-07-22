<?php

namespace App\Services\Menu;

use App\Repositories\Elouquent\MenuRepository;
use App\Repositories\Elouquent\MenuRoleRepository;
use Exception;

class MenuService
{
    public function __construct()
    {
        $this->menuRepository = new MenuRepository();
        $this->menuRoleRepository = new MenuRoleRepository();
    }

    public function getMenuByRole($roleId)
    {
        $menusArrayId = $this->menuRoleRepository->findByFieldWhereReturnArray('role_id', '=', $roleId, 'menu_id');
        $menus = $this->menuRepository->findByFieldWhereIn('id', $menusArrayId);

        return $menus;
    }
}

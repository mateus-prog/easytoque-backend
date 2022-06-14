<?php

namespace App\Repositories\Elouquent;

use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;

class MenuRepository extends AbstractRepository implements MenuRepositoryInterface
{
    protected $model = Menu::class;
}

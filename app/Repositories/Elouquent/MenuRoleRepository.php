<?php

namespace App\Repositories\Elouquent;

use App\Models\MenuRole;
use App\Repositories\Contracts\MenuRoleRepositoryInterface;

class MenuRoleRepository extends AbstractRepository implements MenuRoleRepositoryInterface
{
    protected $model = MenuRole::class;
}

<?php

namespace App\Repositories\Elouquent;

use App\Models\Role;
use App\Repositories\Contracts\RolesRepositoryInterface;

class RolesRepository extends AbstractRepository implements RolesRepositoryInterface
{
    protected $model = Role::class;
}

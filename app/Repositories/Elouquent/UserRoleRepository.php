<?php

namespace App\Repositories\Elouquent;

use App\Models\UserRoles;
use App\Repositories\Contracts\UserRoleRepositoryInterface;

class UserRoleRepository extends AbstractRepository implements UserRoleRepositoryInterface
{
    protected $model = UserRoles::class;
}

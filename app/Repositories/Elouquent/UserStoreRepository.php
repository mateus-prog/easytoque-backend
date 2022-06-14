<?php

namespace App\Repositories\Elouquent;

use App\Models\UserStore;
use App\Repositories\Contracts\UserStoreRepositoryInterface;

class UserStoreRepository extends AbstractRepository implements UserStoreRepositoryInterface
{
    protected $model = UserStore::class;
}

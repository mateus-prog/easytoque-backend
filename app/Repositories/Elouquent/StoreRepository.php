<?php

namespace App\Repositories\Elouquent;

use App\Models\Store;
use App\Repositories\Contracts\StoreRepositoryInterface;

class StoreRepository extends AbstractRepository implements StoreRepositoryInterface
{
    protected $model = Store::class;
}

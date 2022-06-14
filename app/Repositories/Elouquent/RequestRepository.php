<?php

namespace App\Repositories\Elouquent;

use App\Models\Request;
use App\Repositories\Contracts\RequestRepositoryInterface;

class RequestRepository extends AbstractRepository implements RequestRepositoryInterface
{
    protected $model = Request::class;
}

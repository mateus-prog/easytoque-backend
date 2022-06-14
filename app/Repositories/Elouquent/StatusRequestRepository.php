<?php

namespace App\Repositories\Elouquent;

use App\Models\StatusRequest;
use App\Repositories\Contracts\StatusRequestRepositoryInterface;

class StatusRequestRepository extends AbstractRepository implements StatusRequestRepositoryInterface
{
    protected $model = StatusRequest::class;
}

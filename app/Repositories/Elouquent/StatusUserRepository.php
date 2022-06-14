<?php

namespace App\Repositories\Elouquent;

use App\Models\StatusUser;
use App\Repositories\Contracts\StatusUserRepositoryInterface;

class StatusUserRepository extends AbstractRepository implements StatusUserRepositoryInterface
{
    protected $model = StatusUser::class;
}

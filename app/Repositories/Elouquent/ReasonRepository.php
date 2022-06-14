<?php

namespace App\Repositories\Elouquent;

use App\Models\Reason;
use App\Repositories\Contracts\ReasonRepositoryInterface;

class ReasonRepository extends AbstractRepository implements ReasonRepositoryInterface
{
    protected $model = Reason::class;
}

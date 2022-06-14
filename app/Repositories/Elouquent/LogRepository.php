<?php

namespace App\Repositories\Elouquent;

use App\Models\Log;
use App\Repositories\Contracts\LogRepositoryInterface;

class LogRepository extends AbstractRepository implements LogRepositoryInterface
{
    protected $model = Log::class;
}

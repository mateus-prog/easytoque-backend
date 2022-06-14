<?php

namespace App\Repositories\Elouquent;

use App\Models\Bank;
use App\Repositories\Contracts\BankRepositoryInterface;

class BankRepository extends AbstractRepository implements BankRepositoryInterface
{
    protected $model = Bank::class;
}

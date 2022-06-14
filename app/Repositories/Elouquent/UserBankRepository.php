<?php

namespace App\Repositories\Elouquent;

use App\Models\UserBank;
use App\Repositories\Contracts\UserBankRepositoryInterface;

class UserBankRepository extends AbstractRepository implements UserBankRepositoryInterface
{
    protected $model = UserBank::class;
}

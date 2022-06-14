<?php

namespace App\Repositories\Elouquent;

use App\Models\UserCorporate;
use App\Repositories\Contracts\UserCorporateRepositoryInterface;

class UserCorporateRepository extends AbstractRepository implements UserCorporateRepositoryInterface
{
    protected $model = UserCorporate::class;
}

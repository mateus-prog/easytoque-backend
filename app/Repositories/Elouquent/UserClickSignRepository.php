<?php

namespace App\Repositories\Elouquent;

use App\Models\UserClickSign;
use App\Repositories\Contracts\UserClickSignRepositoryInterface;

class UserClickSignRepository extends AbstractRepository implements UserClickSignRepositoryInterface
{
    protected $model = UserClickSign::class;
}

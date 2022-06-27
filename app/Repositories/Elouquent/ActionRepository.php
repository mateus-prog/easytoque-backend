<?php

namespace App\Repositories\Elouquent;

use App\Models\Action;
use App\Repositories\Contracts\ActionRepositoryInterface;

class ActionRepository extends AbstractRepository implements ActionRepositoryInterface
{
    protected $model = Action::class;
}

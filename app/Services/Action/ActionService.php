<?php

namespace App\Services\Action;

use App\Repositories\Elouquent\ActionRepository;
use Exception;

class ActionService
{
    public function __construct()
    {
        $this->actionRepository = new ActionRepository();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function all()
    {
        return $this->actionRepository->all();
    }
}

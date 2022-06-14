<?php

namespace App\Services\State;

use App\Repositories\Elouquent\StateRepository;
use Exception;

class StateService
{
    public function __construct()
    {
        $this->stateRepository = new StateRepository();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function all()
    {
        return $this->stateRepository->all();
    }

    /**
     * Selecione os usuarios conforme o role
     * @param  int  $id
     * @return array
    */
    public function findById($stateId)
    {
        return $this->stateRepository->findById($stateId);
    }
}
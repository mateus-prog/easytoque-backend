<?php

namespace App\Services\StatusRequest;

use App\Repositories\Elouquent\StatusRequestRepository;
use Exception;

class StatusRequestService
{
    public function __construct()
    {
        $this->statusRequestRepository = new StatusRequestRepository();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function all()
    {
        return $this->statusRequestRepository->all();
    }

    /**
     * Selecione os usuarios conforme o role
     * @param  int  $id
     * @return array
    */
    public function findById($statusId)
    {
        return $this->statusRequestRepository->findById($statusId);
    }
}
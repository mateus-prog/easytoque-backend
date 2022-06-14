<?php

namespace App\Services\StatusUser;

use App\Repositories\Elouquent\StatusUserRepository;
use Exception;

class StatusUserService
{
    public function __construct()
    {
        $this->statusUserRepository = new StatusUserRepository();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function all()
    {
        return $this->statusUserRepository->all();
    }

    /**
     * Selecione os usuarios conforme o role
     * @param  int  $id
     * @return array
    */
    public function findById($statusId)
    {
        return $this->statusUserRepository->findById($statusId);
    }
}
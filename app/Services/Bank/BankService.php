<?php

namespace App\Services\Bank;

use App\Repositories\Elouquent\BankRepository;
use Exception;

class BankService
{
    public function __construct()
    {
        $this->bankRepository = new BankRepository();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function all()
    {
        return $this->bankRepository->all();
    }
}

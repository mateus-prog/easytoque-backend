<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Traits\Pagination;

use App\Http\HttpStatus;
use App\Services\Bank\BankService;

class BanksController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $bankService;
    
    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }
    
    public function index()
    {
        $banks = $this->bankService->all();

        return $this->success($banks, HttpStatus::SUCCESS);
    }
}

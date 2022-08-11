<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use App\Services\Log\LogService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;

class LogController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $logService;

    public function __construct(
        LogService $logService
    )
    {
        $this->middleware(["auth", "verified"]);
        $this->logService = $logService;
    }

    public function index()
    {
        $logs = $this->logService->all();

        return $this->success($logs, HttpStatus::SUCCESS);
    }
}

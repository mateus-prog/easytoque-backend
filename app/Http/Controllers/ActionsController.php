<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Traits\Pagination;

use App\Http\HttpStatus;
use App\Services\Action\ActionService;

class ActionsController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $actionService;
    
    public function __construct(ActionService $actionService)
    {
        $this->actionService = $actionService;
    }
    
    public function index()
    {
        $actions = $this->actionService->all();

        return $this->success($actions, HttpStatus::SUCCESS);
    }
}

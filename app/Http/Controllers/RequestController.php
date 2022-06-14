<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use App\Services\Request\RequestService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;

class RequestController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $requestService;
    
    public function __construct(RequestService $requestService)
    {
        $this->middleware(["auth", "verified"]);
        $this->requestService = $requestService;
    }
    
    public function index()
    {
        $requests = $this->requestService->all();

        return $this->success($requests, HttpStatus::SUCCESS);
    }

    public function show($id)
    {
        $request = $this->requestService->findById($id);
        
        return $this->success($request, HttpStatus::SUCCESS);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use App\Services\StatusRequest\StatusRequestService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;

class StatusRequestController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $statusRequestService;
    
    public function __construct(
        StatusRequestService $statusRequestService
    ){
        $this->statusRequestService = $statusRequestService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statusRequests = $this->statusRequestService->all();

        return $this->success($statusRequests, HttpStatus::SUCCESS);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $statusRequests = $this->statusRequestService->findById($id);
        
        return $this->success($statusRequests, HttpStatus::SUCCESS);
    }
}
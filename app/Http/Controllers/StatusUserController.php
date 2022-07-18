<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use App\Repositories\Contracts\StatusUserRepositoryInterface;
use App\Traits\ApiResponser;
use App\Traits\Pagination;

class StatusUserController extends Controller
{
    use ApiResponser;
    use Pagination;
    
    public function __construct(){}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StatusUserRepositoryInterface $model, Request $request)
    {
        $paginated = $this->paginate($request);
        $status = $model->all($paginated["limit"], $paginated["offset"]);

        return $this->success($status, HttpStatus::SUCCESS);
    }

    public function edit(StatusUserRepositoryInterface $model, $id)
    {
        $status = $model->findByFieldWhereReturnObject('id', '=', $id);
        return $this->success($status, HttpStatus::SUCCESS);
    }
}
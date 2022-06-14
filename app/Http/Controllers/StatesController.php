<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use App\Repositories\Contracts\StateRepositoryInterface;
use App\Traits\ApiResponser;
use App\Traits\Pagination;

class StatesController extends Controller
{
    use ApiResponser;
    use Pagination;
    
    public function __construct(){}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StateRepositoryInterface $model, Request $request)
    {
        $paginated = $this->paginate($request);
        $state = $model->all($paginated["limit"], $paginated["offset"]);

        return $this->success($state, HttpStatus::SUCCESS);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(StateRepositoryInterface $model, $id)
    {
        $state = $model->findById($id);
        
        return $this->success(["state" => $state], HttpStatus::SUCCESS);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function showByUf(StateRepositoryInterface $model, $nameUf)
    {
        $uf = $model->findByFieldWhereReturnObject('initials', '=', $nameUf);
        return $this->success($uf, HttpStatus::SUCCESS);
    }
}
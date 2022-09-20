<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use App\Services\Store\StoreService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;
use Exception;

class StoreController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $storeService;

    public function __construct(
        StoreService $storeService
    )
    {
        $this->storeService = $storeService;
    }

    public function index()
    {
        $stores = $this->storeService->all();
        foreach($stores as $store){
            $idStore = $store->id;
            $clientIdMax = $store->client_id;
        }

        dd($idStore, $clientIdMax);

        return $this->success($stores, HttpStatus::SUCCESS);
    }

    public function update($id, Request $request)
    {
        try {
            $this->storeService->update($id, $request->all());

            return response()->noContent();
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}

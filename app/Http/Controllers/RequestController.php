<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use App\Services\Request\RequestService;
use App\Services\Upload\UploadService;
use App\Services\Store\StoreService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

class RequestController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $requestService;
    protected $uploadService;
    protected $storeService;
    
    public function __construct(
        RequestService $requestService,
        UploadService $uploadService,
        StoreService $storeService
    )
    {
        $this->middleware(["auth", "verified"]);
        $this->requestService = $requestService;
        $this->uploadService = $uploadService;
        $this->storeService = $storeService;
    }
    
    public function index()
    {
        $requests = $this->requestService->all();

        return $this->success($requests, HttpStatus::SUCCESS);
    }

    public function getByUser()
    {
        $requests = $this->requestService->getByUser();
        foreach ($requests as $request) {
            $request->url_proof = $request->url_proof != '' ? $this->uploadService->pathFile('storage/'.$request->url_proof) : '';
        }

        return $this->success($requests, HttpStatus::SUCCESS);
    }

    public function show($id)
    {
        $request = $this->requestService->findById($id);
        
        return $this->success($request, HttpStatus::SUCCESS);
    }

    public function update($id, Request $request)
    {
        try {
            //Gate::authorize('update', User::findOrFail($id));

            $input = $request->only(["status_request_id"]);
            $this->requestService->update($id, $input);

            return response()->noContent();
        } catch (AuthorizationException $aE) {
            return $this->error($aE->getMessage(), HttpStatus::FORBIDDEN);
        } catch (ModelNotFoundException $m) {
            return $this->error($m->getMessage(), HttpStatus::NOT_FOUND);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function uploadFileProof(Request $request)
    {
        try {
            $pathNew = $this->uploadService->uploadFileProof($request, 'url_proof', 'proof');
            
            if ($this->uploadService->verifyFile($pathNew)) {
                $requestInf = $this->requestService->findById($request->id);
                //pega o nome do arquivo
                $pathOld = $requestInf->url_proof;

                //apaga o arquivo
                $pathOld != '' ? $this->uploadService->destroyFile($pathOld) : '';
            }
            
            $this->requestService->update($request->id, ['url_proof' => $pathNew, 'status_request_id' => '4']);

            return response()->noContent();
        } catch (AuthorizationException $aE) {
            return $this->error($aE->getMessage(), HttpStatus::FORBIDDEN);
        } catch (ModelNotFoundException $m) {
            return $this->error($m->getMessage(), HttpStatus::NOT_FOUND);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function uploadFileInvoice(Request $request)
    {
        try {
            $pathNew = $this->uploadService->uploadFileInvoice($request, 'url_invoice', 'invoice');
            
            if ($this->uploadService->verifyFile($pathNew)) {
                $requestInf = $this->requestService->findById($request->id);
                //pega o nome do arquivo
                $pathOld = $requestInf->url_proof;

                //apaga o arquivo
                $pathOld != '' ? $this->uploadService->destroyFile($pathOld) : '';
            }
            
            $this->requestService->update($request->id, ['url_proof' => $pathNew, 'status_request_id' => '4']);

            return response()->noContent();
        } catch (AuthorizationException $aE) {
            return $this->error($aE->getMessage(), HttpStatus::FORBIDDEN);
        } catch (ModelNotFoundException $m) {
            return $this->error($m->getMessage(), HttpStatus::NOT_FOUND);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function requestStore(string $type){
        $requestsStore = $this->storeService->storeMagento($type);

        return $this->success($requestsStore, HttpStatus::SUCCESS);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use App\Services\Request\RequestService;
use App\Services\Upload\UploadService;
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
    
    public function __construct(
        RequestService $requestService,
        UploadService $uploadService
    )
    {
        $this->middleware(["auth", "verified"]);
        $this->requestService = $requestService;
        $this->uploadService = $uploadService;
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
            $request->url_invoice = $request->url_invoice != '' ? $this->uploadService->pathFile('storage/'.$request->url_invoice) : '';
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

    public function upload(Request $request)
    {
        try {
            $pathNew = $this->uploadService->uploadFileInvoice($request, 'url_invoice', 'invoices');
            
            if ($this->uploadService->verifyFile($pathNew)) {
                $requestInf = $this->requestService->findById($request->id);
                //pega o nome do arquivo
                $pathOld = $requestInf->url_invoice;

                //apaga o arquivo
                $pathOld != '' ? $this->uploadService->destroyFile($pathOld) : '';
            }
            
            $this->requestService->update($request->id, ['url_invoice' => $pathNew, 'status_request_id' => '4']);

            return response()->noContent();
        } catch (AuthorizationException $aE) {
            return $this->error($aE->getMessage(), HttpStatus::FORBIDDEN);
        } catch (ModelNotFoundException $m) {
            return $this->error($m->getMessage(), HttpStatus::NOT_FOUND);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}

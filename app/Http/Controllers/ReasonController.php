<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use App\Services\Reason\ReasonService;
use App\Services\Request\RequestService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class ReasonController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $reasonService;
    protected $requestService;

    public function __construct(
        ReasonService $reasonService,
        RequestService $requestService    
    )
    {
        $this->middleware(["auth", "verified"]);
        $this->reasonService = $reasonService;
        $this->requestService = $requestService;
    }

    public function store(Request $request)
    {
        try {  
            $input = $request->only(["reason", "request_id"]);
            $reason = $this->reasonService->store($input);

            $this->requestService->update($input['request_id'], ['status_request_id' => 3]);

            /*$messageLog = $input['reason'];
            $actionId = 1;
            $idUserLog = Auth::user()->id;

            $log = Log::createLog($idUserLog, $messageLog, $actionId);
            $this->logService->store($log);*/

            return $this->success($reason, HttpStatus::CREATED);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
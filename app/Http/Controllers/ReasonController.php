<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use App\Http\Requests\User\UserRequest;
use App\Services\Reason\ReasonService;
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

    public function __construct(
        ReasonService $reasonService    
    )
    {
        $this->middleware(["auth", "verified"]);
        $this->reasonService = $reasonService;
    }

    public function store(UserRequest $request)
    {
        try {  
            $input = $request->only(["reason", "request_id"]);
            $reason = $this->reasonService->store($input);

            return $this->success($reason, HttpStatus::CREATED);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}
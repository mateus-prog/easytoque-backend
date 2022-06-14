<?php

namespace App\Services\Reason;

use App\Repositories\Elouquent\ReasonRepository;
use Exception;

class ReasonService
{
    public function __construct()
    {
        $this->reasonRepository = new ReasonRepository();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findById(int $id)
    {
        $reason = $this->reasonRepository->findById($id);

        return $reason;
    }

    public function store(array $request)
    {
        try { 
            return $this->reasonRepository->store($request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
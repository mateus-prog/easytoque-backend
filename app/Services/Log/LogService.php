<?php

namespace App\Services\Log;

use App\Repositories\Elouquent\UserRepository;
use App\Repositories\Elouquent\LogRepository;
use App\Models\Log;
use Exception;

class LogService
{
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->logRepository = new LogRepository();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function all()
    {
        $logs = $this->logRepository->all();

        foreach($logs as $log){
            $user = $this->userRepository->findById($log->user_id);
            $log->user_id = $user->first_name;
        }

        return $logs;
    }

    public function filterLogs($request)
    {
        $logs = $this->logRepository->findByFieldWhereReturnObject("message", "like", "%".$request["message"]."%");
        //$logs = Log::where("message", "like", "%".$request["message"]."%");
        /*if($request["user_id"] != ""){
            $logs = $logs->where("user_id", $request["user_id"]);
        }*/

        //$logsToArray = $logs->get()->toArray();

        foreach($logs as $log){
            $user = $this->userRepository->findById($log->user_id);
            $log->user_id = $user->first_name;
        }

        return $logs;
    }

    public function store(array $request)
    {
        try {
            return $this->logRepository->store($request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}

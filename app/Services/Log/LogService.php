<?php

namespace App\Services\Log;

use App\Repositories\Elouquent\UserRepository;
use App\Repositories\Elouquent\LogRepository;
use App\Repositories\Elouquent\ActionRepository;
use Exception;

class LogService
{
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->logRepository = new LogRepository();
        $this->actionRepository = new ActionRepository();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function all()
    {
        $logs = $this->logRepository->all();

        foreach($logs as $log){
            $user = $this->userRepository->findById($log->user_changed_id);
            $log->user_changed_id = $user->first_name . ' ' . $user->last_name;

            $user = $this->userRepository->findById($log->user_modified_id);
            $log->user_modified_id = $user->first_name . ' ' . $user->last_name;

            $action = $this->actionRepository->findById($log->action_id);
            $log->action_id = $action->display_name;
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

<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

abstract class Log {

	public static function createLog($userId, $message, $actionId, $status = true)
    {
        $user_changed_id = Auth::user() ? Auth::user()->id : $userId;

        $data = array(
            'user_changed_id' => $user_changed_id,
            'user_modified_id' => $userId,
            'action_id' => $actionId,
            'message' => $message,
            'status' => $status
        );

        return $data;
    }
}
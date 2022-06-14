<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

abstract class Log {

	public static function createLog($message)
    {
        $data = array(
            'user_id' => Auth::user()->id,
            'message' => $message
        );

        return $data;
    }
}
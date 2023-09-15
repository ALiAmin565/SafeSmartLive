<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ResponseJson
{

    public function success($message, $status = 200)
    {
        return response()->json(
            [
                'status' => $status,
                'success' => true,
                'message' => $message,


            ]
        );
    }

    public function error($message, $status = 400)
    {
        return response()->json(
            [
                'success' => false,
                'message' => $message,
                'status' => $status,

            ]
        );
    }
}

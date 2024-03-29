<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function exceptionError($exception, $status = 400)
    {
        if ((int)$status > 599 || (int)$status == 0) $status = 500;

        return response()->json([
            'meta' => [
                "success" => false,
                'error' => is_array($exception) ? $exception : $exception
            ]
        ], $status);
    }

    public function messageSuccess($message = "Success", $status = 200)
    {
        return response()->json([
            'meta' => [
                "success" => true,
                'message' => $message
            ]
        ], $status);
    }

    public function respond($data)
    {
        return response()->json($data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    public function exceptionError($exception, $status = 500)
    {
        if ($status > 599) $status = 500;

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

    public function emptyDataSuccess($message = "Success", $status = 200)
    {
        return response()->json([
            'data' => (object)[],
            'meta' => [
                'success' => true,
                'message' => $message,
                'pagination' => (object)[],
            ],
        ], $status);
    }

    /**
     * Returns a 200 response.
     *
     * @param  array  $data
     * @return \Illuminate\Http\Response
     */
    public function respond($data)
    {
        return response()->json($data);
    }
}

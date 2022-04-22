<?php

namespace App\Http;

trait CustomResponse
{


    protected function onSuccess($data, string $message = '', int $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function onError(int $code, string $message = ''): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
        ], $code);
    }


}

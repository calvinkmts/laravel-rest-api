<?php

namespace App\Traits;

use function response;

trait RespondsWithHttpStatus
{
    protected function success($message, $data = [], $status = 200)
    {
        return response([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'status_code' => $status,
        ], $status);
    }

    protected function failure($message, $error = [], $status = 422)
    {
        return response([
            'message' => $message,
            'status_code' => $status,
            'error' => $error,
        ], $status);
    }
}

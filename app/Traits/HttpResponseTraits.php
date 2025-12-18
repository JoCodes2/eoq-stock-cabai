<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait HttpResponseTraits
{
    protected function success(mixed $payload = null, string $message = 'success', int $code = 200)
    {
        $data = [
            'code' => $code,
            'message' => $message,
            'data' => $payload
        ];

        return response()->json($data);
    }

    protected function dataNotFound($message = 'Data not found', int $code = 404)
    {
        return response()->json([
            'code' => $code,
            'message' => $message
        ]);
    }

    protected function idOrDataNotFound($message = 'ID or data not found', int $code = 404)
    {
        return response()->json([
            'code' => $code,
            'message' => $message
        ]);
    }

    protected function delete($message = 'Success delete ', int $code = 200)
    {
        return response()->json([
            'code' => $code,
            'message' => $message
        ]);
    }

    protected function error(string $message = 'error', int $code = 400, mixed $payload = null, mixed $class = null, string $method = '')
    {
        $data = [
            'code' => $code,
            'message' => $message
        ];

        if ($payload) {
            Log::error($class, [
                'Message: ' . $payload->getMessage(),
                'Method: '  . $method,
                'On File: ' . $payload->getFile(),
                'On Line: ' . $payload->getLine()
            ]);
        }
        return response()->json($data);
    }
}

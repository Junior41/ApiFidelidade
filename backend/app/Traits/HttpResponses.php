<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\MessageBag;

trait HttpResponses{
    public function success(string $message = "", string|int $status = 0, array|Model|JsonResource $data = []): JsonResponse{
        return response()->json([
            'message' => $message,
            'status' => $status,
            'data'=> $data
        ], $status);
    }

    public function error(string $message = "", string|int $status = 0, array|MessageBag $errors = [], array $data = []): JsonResponse{
        return response()->json([
            'message' => $message,
            'status' => $status,
            'errors' => $errors,
            'data'=> $data
        ], $status);
    }
}

?>
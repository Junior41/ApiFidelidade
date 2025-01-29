<?php

namespace App\Exceptions;

use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class InsufficientBalanceException extends Exception
{
    public $statusCode;
    use HttpResponses;

    public function __construct($message = "Insufficient balance for exchange", $statusCode = 400)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function render($request): JsonResponse
    {
        return $this->error(
            $this->getMessage()
            ,$this->statusCode);
    }
}
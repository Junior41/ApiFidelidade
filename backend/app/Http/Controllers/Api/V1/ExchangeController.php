<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateExchangeRequest;
use App\Http\Resources\V1\ExchangeResource;
use App\Services\ExchangeService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    private $exchangeService;
    use HttpResponses;

    public function __construct(ExchangeService $exchangeService){
        $this->exchangeService = $exchangeService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateExchangeRequest $request)
    {
        $data = $request->validated();

        try{
            $exchange = $this->exchangeService->makeExchange($data);

            return $this->success("Exchange made successfully", 200, new ExchangeResource($exchange->load(['client', 'reward'])));
        }catch (NotFoundException|InsufficientBalanceException $e) { 
            return $e->render($request);
        }

    }

    
}

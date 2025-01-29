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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

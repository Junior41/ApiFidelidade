<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTransactionRequest;
use App\Http\Resources\V1\TransactionResource;
use App\Services\TransactionService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    use HttpResponses;
    private $transacationService;

    public function __construct(TransactionService $transactionService){
        $this->transacationService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTransactionRequest $request): JsonResponse
    {
        $data = $request->validated();

        try{
            $transaction = $this->transacationService->createTransaction($data);

            return $this->success("Transaction registered successfully", 200, new TransactionResource($transaction->load('client')));
        }catch (NotFoundException $e) { 
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

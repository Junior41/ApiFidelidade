<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\V1\ClientAndRewardResource;
use App\Http\Resources\V1\ClientResource;
use App\Models\Client;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        $clients = Client::with('exchange.reward')->get();

        return $this->success("Successful listing", 200, ClientResource::collection($clients));
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateClientRequest $request):JsonResponse
    {
        $validationData = $request->validated();

        if (Client::where('email', $validationData['email'])->exists())
            return $this->error('Validation failed.', 422, ['This email is already in use']); 
        

        $client = Client::create($validationData);

        return $this->success('Client created successfully',
                            201,
                        new ClientResource($client));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::where('id', $id)->first();

        if ($client)
            return $this->success('Client found', 200, new ClientResource($client));
        else
            return $this->error('Client not found', 404);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, string $id):JsonResponse
    {
        $client = Client::where('id', $id)->first();
        $validatedData = $request->validated();

        if (!$client)
            return $this->error('Client not found', 404);

        $updated = $client->update($validatedData);

        return $this->success("Client updated", 200, new ClientResource($client));
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id):JsonResponse
    {
        $client = Client::where('id', $id)->first();

        if (!$client)
            return $this->error('Client not found', 404);

        $updated = $client->delete();

        return $this->success("Client excluded", 200, new ClientResource($client));
        
    }

    public function getRewards($id): JsonResponse{
        $client = Client::where('id', $id)->first();

        if ($client)
            return $this->success('Client found', 200, new ClientAndRewardResource($client));
        else
            return $this->error('Client not found', 404);
    }
}

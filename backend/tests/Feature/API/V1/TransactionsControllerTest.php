<?php

namespace Tests\Feature\API\V1;

use Illuminate\Http\Response;
use Tests\TestCase;
use Faker\Generator as Faker;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Reward;
use App\Models\Transaction;
use App\Models\Exchange;
use Illuminate\Foundation\Testing\WithFaker;


class TransactionsControllerTest extends TestCase
{
    use WithFaker;

    public function testExchangeCreatedSuccessfully(){
        $client = Client::create([
            'name' => $this->faker->firstName,
            'email' => $this->faker->email
        ]);

        $reward = Reward::create([
            'name' => $this->faker->name, 
            'pointsCost' => $this->faker->randomDigitNotNull
        ]);

        $this->transactionService->createTransaction([
            "value" => $reward->pointsCost * 5,
            'clientId' => $client->id,
        ]);

        $exchange = [
            'clientId' => $client->id,
            'rewardId' => $reward->id
        ];

        $this->json('post', '/api/v1/exchange', $exchange)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'message' => "Exchange made successfully",
                'status' => 200,
                'data' => [
                        'client' => [
                            'id' => $client->id,
                            'name' => $client->name,
                            'email' => $client->email,
                            'balance' => 'R$' . number_format($client->balance, 2, ',', '.'),
                            'points' => $client->balance >= 5 ? intdiv($client->balance, 5) : 0,
                        ],
                        'reward' => [ 
                            "id" => $reward->id, 
                            "name" => $reward->name, 
                            "pointsCost" => $reward->pointsCost, 
                        ],
                        "exchangeDate" => Carbon::parse(Exchange::latest()->first()->created_at)->format('d/m/y H:i:s'), 

                ] 
            ]);
    }

    public function testMakeExchangeWithInvalidClient(){
        $client = Client::create([
            'name' => $this->faker->firstName,
            'email' => $this->faker->email
        ]);

        $reward = Reward::create([
            'name' => $this->faker->name, 
            'pointsCost' => $this->faker->randomDigitNotNull
        ]);

        $this->transactionService->createTransaction([
            "value" => $reward->pointsCost * 5,
            'clientId' => $client->id,
        ]);

        $exchange = [
            'clientId' => $client->id + 1,
            'rewardId' => $reward->id
        ];

        $this->json('post', '/api/v1/exchange', $exchange)
            ->assertStatus(404)
            ->assertExactJson([
                'message' => "Client not found",
                'status' => 404,
                'errors' => [],
                'data' => []
            ]);
    }

    public function testMakeExchangeWithInvalidReward(){
        $client = Client::create([
            'name' => $this->faker->firstName,
            'email' => $this->faker->email
        ]);

        $reward = Reward::create([
            'name' => $this->faker->name, 
            'pointsCost' => $this->faker->randomDigitNotNull
        ]);

        $this->transactionService->createTransaction([
            "value" => $reward->pointsCost * 5,
            'clientId' => $client->id,
        ]);

        $exchange = [
            'clientId' => $client->id,
            'rewardId' => $reward->id + 1
        ];

        $this->json('post', '/api/v1/exchange', $exchange)
            ->assertStatus(404)
            ->assertExactJson([
                'message' => "Reward not found",
                'status' => 404,
                'errors' => [],
                'data' => []
            ]);
    }

    public function testMakeExchangeWithInsufficientBalance(){
        $client = Client::create([
            'name' => $this->faker->firstName,
            'email' => $this->faker->email
        ]);

        $reward = Reward::create([
            'name' => $this->faker->name, 
            'pointsCost' => $this->faker->randomDigitNotNull
        ]);

        $exchange = [
            'clientId' => $client->id,
            'rewardId' => $reward->id
        ];

        $this->json('post', '/api/v1/exchange', $exchange)
            ->assertStatus(400)
            ->assertExactJson([
                'message' => "Insufficient balance for exchange",
                'status' => 400,
                'errors' => [],
                'data' => []
            ]);
    }

    public function testExchangeRegistrationWithoutRequiredParams(){

        $this->json('post', '/api/v1/exchange', [])
            ->assertStatus(422)
            ->assertExactJson([
                'message' => "Validation failed.",
                'status' => 422,
                'errors' => ['The clientId attribute is required.', 'The rewardId attribute is required.'],
                'data' => [] 
            ]);
    }
}

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

    public function testTransactionCreatedSuccessfully(){
        $client = Client::create([
            'name' => $this->faker->firstName,
            'email' => $this->faker->email
        ]);

        $transaction = [
            'clientId' => $client->id,
            'value' => 10
        ];

        $this->json('post', '/api/v1/transactions', $transaction)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => "Transaction registered successfully",
                'status' => 200,
                'data' => [
                    'client' => [
                        'id' => $client->id,
                        'name' => $client->name,
                        'email' => $client->email,
                        'balance' => 'R$' . number_format($transaction['value'], 2, ',', '.'),
                        'points' => $transaction['value'] >= 5 ? intdiv($transaction['value'], 5) : 0,
                    ],
                    "value" => 'R$' . number_format($transaction['value'], 2, ',', '.'),
                ] 
            ]);
    }

    public function testRegisterTransactionWithInvalidClient(){
        $client = Client::create([
            'name' => $this->faker->firstName,
            'email' => $this->faker->email
        ]);

        $transaction = [
            "value" => 0,
            'clientId' => $client->id + 1,
        ];

        $this->json('post', '/api/v1/transactions', $transaction)
            ->assertStatus(404)
            ->assertExactJson([
                'message' => "Client not found",
                'status' => 404,
                'errors' => [],
                'data' => []
            ]);
    }

    public function testRegisterTransactionWithInvalidValue(){
        $client = Client::create([
            'name' => $this->faker->firstName,
            'email' => $this->faker->email
        ]);

        $transaction = [
            "value" => 'abc',
            'clientId' => $client->id,
        ];

        $this->json('post', '/api/v1/transactions', $transaction)
            ->assertStatus(422)
            ->assertExactJson([
                'message' => "Validation failed.",
                'status' => 422,
                'errors' => ['The value must be a number'],
                'data' => []
            ]);
    }

    public function testRegisterTransactionWithWithValueLessThanZero(){
        $client = Client::create([
            'name' => $this->faker->firstName,
            'email' => $this->faker->email
        ]);

        $transaction = [
            "value" => -1,
            'clientId' => $client->id,
        ];

        $this->json('post', '/api/v1/transactions', $transaction)
            ->assertStatus(422)
            ->assertExactJson([
                'message' => "Validation failed.",
                'status' => 422,
                'errors' => ["Transaction amount must be greater than 0"],
                'data' => []
            ]);
    }

    public function testRegisterTransactionWithoutRequiredParams(){

        $this->json('post', '/api/v1/transactions', [])
            ->assertStatus(422)
            ->assertExactJson([
                'message' => "Validation failed.",
                'status' => 422,
                'errors' => ['The value attribute is required.', 'The clientId attribute is required.'],
                'data' => [] 
            ]);
    }
}

<?php

namespace Tests\Feature\API\V1;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Generator as Faker;
use App\Models\Client;
use App\Models\Reward;
use App\Models\Transaction;
use App\Models\Exchange;
use Carbon\Carbon;

class ClientsControllerTest extends TestCase
{
    use WithFaker;

    public function testIndexReturnsDataInValidFormat(): void
    {
        $this->json('get', 'api/v1/clients')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'balance',
                        'points'
                    ]
                ]

            ]);
    }

    public function testClientsCreatedSuccessfully(){
        $client = [
            'name' => $this->faker->firstName,
            'email' => $this->faker->email
        ];

        $this->json('post', 'api/v1/clients', $client)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'message',
                'status',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'balance',
                    'points'
                ]
            ]);
    }

    public function testEmailValidationWhenCreatingTheClient(){
        $client = Client::create(
            [
                'name' => $this->faker->firstName,
                'email' => $this->faker->email
            ]
        );

        $invalidClient = [
            'name' => $client->name,
            'email' => $client->email
        ];

        $this->json('post', 'api/v1/clients', $invalidClient)
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'Validation failed.',
                'status' => 422,
                'errors' => ['This email is already in use'],
                'data' => []
            ]);
    }

    public function testClientRegistrationWithoutRequiredParams(){
        $client =  [
            
        ];

        $this->json('post', 'api/v1/clients', $client)
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'Validation failed.',
                'status' => 422,
                'errors' => ['The name attribute is required.', 'The email attribute is required.'],
                'data' => []
            ]);
    }

    public function testClientIsShowCorrectly(){
        $client = Client::create(
            [
                'name' => $this->faker->firstName,
                'email' => $this->faker->email
            ]
        );

        $this->json('get', 'api/v1/clients/'.$client->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'message' => "Client found",
                'status' => 200,
                'data' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'balance' => 'R$0,00',
                    'points' => 0
                ]
            ]);
    }

    public function testShowForMissingClient(){
        $this->json('get', 'api/v1/clients/-1')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson([
                'message' => "Client not found",
                'status' => 404,
                'errors' => [],
                'data' => []
            ]);
    }

    public function testUpdateForMissingClient(){
        $this->json('put', 'api/v1/clients/-1')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson([
                'message' => "Client not found",
                'status' => 404,
                'errors' => [],
                'data' => []
            ]);
    }

    public function testDeleteForMissingClient(){
        $this->json('delete', 'api/v1/clients/-1')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson([
                'message' => "Client not found",
                'status' => 404,
                'errors' => [],
                'data' => []
            ]);
    }

    public function testClientIsDestroyed(){
        $clientData = [
            "name" => $this->faker->name,
            "email" => $this->faker->email
        ];

        $client = Client::create($clientData);

        $this->json('delete', 'api/v1/clients/' . $client->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('clients', $clientData);
    }

    public function testUpdateClientReturnsCorrectData(){
        $client = Client::create(
            [
                "name" => $this->faker->name,
                "email" => $this->faker->email
            ]
        );

        $clientData = [
            "name" => $this->faker->name,
            "email" => $this->faker->email
        ];

        $this->json('put', 'api/v1/clients/' . $client->id, $clientData)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'message' => "Client updated",
                'status' => 200,
                'data' => [
                    'id' => $client->id,
                    'name' => $clientData['name'],
                    'email' => $clientData['email'],
                    'balance' => 'R$' . number_format($client->balance, 2, ',', '.'),
                    'points' => $client->balance >= 5 ? intdiv(intdiv($client->balance, 5), 5) : 0
                ]
            ]);
    }

    public function testGetInvertimentsForUser(){
        $client = Client::create(
            [
                "name" => $this->faker->name,
                "email" => $this->faker->email
            ]
        );

        $reward = Reward::create([
            'name' => $this->faker->name, 
            'pointsCost' => $this->faker->randomDigitNotNull
        ]);

        Transaction::create([
            "value" => $reward->pointsCost * 5,
            'clientId' => $client->id,
        ]);

        $exchange = Exchange::create([
            'clientId' => $client->id,
            'rewardId' => $reward->id
        ]);

        $this->json('get', '/api/v1/clients/' . $client->id . '/rewards')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'message' => "Client found",
                'status' => 200,
                'data' => [
                        'id' => $client->id,
                        'name' => $client->name,
                        'email' => $client->email,
                        'balance' => 'R$' . number_format($client->balance, 2, ',', '.'),
                        'points' => $client->balance >= 5 ? intdiv($client->balance, 5) : 0,
                        'rewards' => [ 
                            [
                                "id" => $reward->id, 
                                "name" => $reward->name, 
                                "pointsCost" => $reward->pointsCost, 
                                "exchangeDate" => Carbon::parse($exchange->created_at)->format('d/m/y H:i:s'), 
                            ]
                        ]
                ] 
            ]);
    }
}

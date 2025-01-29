<?php
namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\NotFoundException;
use App\Jobs\SendClientEmail;
use App\Models\Client;
use App\Models\Exchange;
use App\Models\Reward;
use App\Models\Transaction;

class ExchangeService
{
    public function makeExchange(array $data)
    {
        $client = Client::find($data['clientId']);
        $reward = Reward::find($data['rewardId']);
        
        if (!$client)
            throw new NotFoundException("Client not found", 404);
        if (!$reward)
            throw new NotFoundException("Reward not found", 404);


        if($client->balance < $reward->pointsCost * 5)
            throw new InsufficientBalanceException();


        $client->balance -= $reward->pointsCost * 5;

        $exchange = Exchange::create($data);
        
        $client->save();

        SendClientEmail::dispatch($client, "emails.SuccessfulExchange", "Recompensa resgatada!", ['client' => $client, 'reward' => $reward]);

        return $exchange;
    }
}

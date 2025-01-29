<?php
namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Jobs\SendClientEmail;
use App\Models\Client;
use App\Models\Transaction;

class TransactionService
{
    public function createTransaction(array $data)
    {
        $client = Client::find($data['clientId']);
        
        if (!$client)
            throw new NotFoundException("Client not found", 404);

        $oldBudget = $client->balance;

        $client->balance += $data['value'];

        $pointsEarned = intdiv($oldBudget, 5) - intdiv($client->balance, 5); 


        if($pointsEarned != 0){
            $dataEmail = [
                'client' => $client, 
                'purchaseValue' => $data['value']
            ];
            
            SendClientEmail::dispatch($client, "emails.PointsGain", "Parabéns, você ganhou pontos!", $dataEmail);
        }

        $transaction = Transaction::create($data);
        
        $client->save();

        return $transaction;
    }
}

<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ClientAndRewardResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'balance' => 'R$' . number_format($this->balance, 2, ',', '.'),
            'points' => $this->balance >= 5 ? intdiv($this->balance, 5) : 0,
            'rewards' => $this->exchange->map(function($exchange) { 
                return [ 
                    "id" => $exchange->reward->id, 
                    "name" => $exchange->reward->name, 
                    "pointsCost" => $exchange->reward->pointsCost, 
                    "exchangeDate" => Carbon::parse($exchange->created_at)->format('d/m/y H:i:s'), 
                ]; 
            }),
        ];
    }
}
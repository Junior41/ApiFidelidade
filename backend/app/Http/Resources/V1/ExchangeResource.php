<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ExchangeResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'client' => new ClientResource($this->client),
            'reward' => new RewardResource($this->reward),
            'exchangeDate' => Carbon::parse($this->created_at)->format('d/m/y H:i:s'),
        ];
    }
}
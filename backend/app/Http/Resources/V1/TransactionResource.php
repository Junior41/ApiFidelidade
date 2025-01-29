<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TransactionResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'client' => new ClientResource($this->client),
            'id' => $this->id,
            'value' => 'R$' . number_format($this->value, 2, ',', '.'),
            'created_at' => Carbon::parse($this->created_at)->format('d/m/y H:i:s'),
        ];
    }
}
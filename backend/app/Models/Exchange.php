<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    protected $fillable = [
        'rewardId',
        'clientId',
    ];

    public function client(){
        return $this->belongsTo(Client::class, 'clientId');
    }
    public function reward(){
        return $this->belongsTo(Reward::class, 'rewardId');
    }
}

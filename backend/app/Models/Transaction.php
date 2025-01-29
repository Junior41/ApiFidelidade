<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        "value",
        'clientId',
    ];

    public function client(){
        return $this->belongsTo(Client::class, 'clientId');
    }
}

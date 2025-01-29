<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'balance',
        'points',
    ];

    public function exchange()
    {
        return $this->hasMany(Exchange::class, 'clientId');
    }
}

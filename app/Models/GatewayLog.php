<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GatewayLog extends Model
{
    protected $fillable = [
        'reference_id',
        'transaction_id',
        'status',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}

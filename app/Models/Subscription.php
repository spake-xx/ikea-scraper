<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
            'send_to',
            'queries'
        ];
    
        protected $casts = [
            'send_to' => 'array',
            'queries' => 'array'
        ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'subscription_id',
        'result'
    ];

    protected $casts = [
        'result' => 'array'
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}

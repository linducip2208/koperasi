<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookEvent extends Model
{
    protected $table = 'webhook_events';

    protected $fillable = [
        'payment_provider_id', 'payment_id', 'order_id',
        'amount', 'status', 'raw', 'processed_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'raw' => 'array',
        'processed_at' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }
}

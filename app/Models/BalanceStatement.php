<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BalanceStatement extends Model
{
    protected $fillable = [
        'client_id',
        'balance',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'balance' => 'decimal:2',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

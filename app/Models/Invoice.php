<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Receipt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'client_id',
        'amount',
        'invoice_date',
        'status',
        'file_path',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }
}

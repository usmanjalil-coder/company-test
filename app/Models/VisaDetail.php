<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisaDetail extends Model
{
    protected $fillable = [
        'client_id',
        'visa_type',
        'expiry_date',
        'reminder_sent',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'reminder_sent' => 'boolean',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

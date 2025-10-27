<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InitialContact extends Model
{
    protected $fillable = [
        'client_id',
        'advice_notes',
        'contact_date',
    ];

    protected $casts = [
        'contact_date' => 'date',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

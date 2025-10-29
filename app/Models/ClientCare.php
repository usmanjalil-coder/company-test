<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientCare extends Model
{
    protected $fillable = [
        'client_id',
        'care_notes',
        'date',
    ];

    protected $table = 'client_care';

    protected $casts = [
        'date' => 'date',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

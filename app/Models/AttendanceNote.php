<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceNote extends Model
{
    protected $fillable = [
        'client_id',
        'note',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

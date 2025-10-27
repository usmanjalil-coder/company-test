<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutcomeLetter extends Model
{
    protected $fillable = [
        'client_id',
        'content',
        'date',
        'file_path',
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

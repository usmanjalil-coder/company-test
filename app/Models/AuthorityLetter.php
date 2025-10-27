<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthorityLetter extends Model
{
    protected $fillable = [
        'company_id',
        'client_id',
        'content',
        'date',
        'file_path',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

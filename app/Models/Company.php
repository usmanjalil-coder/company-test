<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'company_name',
        'company_address',
        'contact_number',
        'email_address',
        'solicitor_name',
        'regulated_by',
        'company_reg_number',
        'logo_path',
        'accreditor_logos',
    ];

    protected $casts = [
        'accreditor_logos' => 'array', // JSON field ko array ke tor pe handle karega
    ];

    // Relationships
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function authorityLetters(): HasMany
    {
        return $this->hasMany(AuthorityLetter::class);
    }
}

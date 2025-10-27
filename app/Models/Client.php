<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Document;
use App\Models\ClientCare;
use App\Models\VisaDetail;
use App\Models\ClosingLetter;
use App\Models\OutcomeLetter;
use App\Models\AttendanceNote;
use App\Models\CoveringLetter;
use App\Models\FollowupLetter;
use App\Models\InitialContact;
use App\Models\AuthorityLetter;
use App\Models\LedgerStatement;
use App\Models\BalanceStatement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'address',
    ];

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function authorityLetters(): HasMany
    {
        return $this->hasMany(AuthorityLetter::class);
    }

    public function clientCare(): HasMany
    {
        return $this->hasMany(ClientCare::class);
    }

    public function initialContacts(): HasMany
    {
        return $this->hasMany(InitialContact::class);
    }

    public function coveringLetters(): HasMany
    {
        return $this->hasMany(CoveringLetter::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function attendanceNotes(): HasMany
    {
        return $this->hasMany(AttendanceNote::class);
    }

    public function followupLetters(): HasMany
    {
        return $this->hasMany(FollowupLetter::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function outcomeLetters(): HasMany
    {
        return $this->hasMany(OutcomeLetter::class);
    }

    public function closingLetters(): HasMany
    {
        return $this->hasMany(ClosingLetter::class);
    }

    public function ledgerStatements(): HasMany
    {
        return $this->hasMany(LedgerStatement::class);
    }

    public function balanceStatements(): HasMany
    {
        return $this->hasMany(BalanceStatement::class);
    }

    public function visaDetails(): HasMany
    {
        return $this->hasMany(VisaDetail::class);
    }
}

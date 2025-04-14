<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Time extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'date',
        'total_hours_worked',
        'total_minutes_worked',
        'is_special',
        'contract_classification_id',
        'billed'
    ];

    public function contractClassification(): BelongsTo
    {
        return $this->belongsTo(ContractClassification::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_classifications');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contract_classifications');
    }
}

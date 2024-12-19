<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractNote extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "date",
        "note",
        "attachments",
        'contract_classification_id'
    ];

    public $casts = [
        'attachments' => 'array'
    ];
    public function contractClassification(): BelongsTo {
        return $this->belongsTo(ContractClassification::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(ContractClassification::class, 'contract_classifications');
    }
    public function contract(): BelongsTo
    {
        return $this->belongsTo(ContractClassification::class, 'contract_classifications');
    }
}

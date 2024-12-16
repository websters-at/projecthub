<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        "original_filename",
        "note",
        "note_attachment",
        'contract_classification_id'
    ];

    public $casts = [
        'note_attachment' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(ContractClassification::class, 'contract_classifications');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(ContractClassification::class, 'contract_classifications');
    }

}

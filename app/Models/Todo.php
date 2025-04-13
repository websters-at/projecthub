<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "due_to",
        "description",
        "attachments",
        "contract_classification_id",
        "is_done",
        "priority"
    ];

    public $casts = [
        'attachments' => 'array',
    ];
    public function contract_classification(): BelongsTo{
        return $this->belongsTo(ContractClassification::class);
    }
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}

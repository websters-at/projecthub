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
        "description",
        "note",
        "attachments",
        'contract_id'
    ];

    public $casts = [
        'attachments' => 'array'
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}

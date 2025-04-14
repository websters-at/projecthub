<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Call extends Model
{
    use HasFactory;

    protected $fillable = [
        "contract_classification_id",
        "customer_id",
        "name",
        "on_date",
        "is_done",
        "description",
        "zip_code",
        "city",
        "state",
        "country",
        "due_to",
        "address",
        "address2",
        "address3",
    ];

    public function contract_classification(): BelongsTo{
        return $this->belongsTo(ContractClassification::class);
    }
    public function call_notes(): HasMany{
        return $this->hasMany(CallNote::class);
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Prompts\Note;

class ContractClassification extends Model
{
    use HasFactory;

    protected $fillable = [
      "user_id",
      "contract_id"
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
    public function calls(): HasMany
    {
        return $this->hasMany(Call::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function times(): HasMany
    {
        return $this->hasMany(Time::class);
    }

    public function bill(): HasMany{
        return $this->hasMany(Bill::class);
    }
}

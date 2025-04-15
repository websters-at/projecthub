<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Bill extends Model
{
    use HasFactory;
    protected $fillable = [
        "contract_classification_id",
        "name",
        "is_payed",
        "is_flat_rate",
        "flat_rate_amount",
        "flat_rate_hours",
        "flat_rate_minutes",
        "hourly_rate",
        "attachments",
        "created_on",
        "billed",
        "due_to",
        "description"
    ];
    public $casts = [
        'attachments' => 'array'
    ];
    public function contractClassification(): BelongsTo {
        return $this->belongsTo(ContractClassification::class);
    }
    public function users(): BelongsTo {
        return $this->belongsTo(ContractClassification::class, 'contract_classifications');
    }
    public function contract(): BelongsTo {
        return $this->belongsTo(ContractClassification::class, 'contract_classifications');
    }
    public function times(): HasMany
    {
        return $this->hasMany(
            Time::class,
            'contract_classification_id',
            'contract_classification_id'
        );
    }

    protected static function booted()
    {
        static::created(function (Bill $bill) {
            if ($bill->is_flat_rate) {
                $bill->times()->create([
                    'contract_classification_id' => $bill->contract_classification_id,
                    'user_id' => Auth::id(),
                    'date' => now(),
                    'total_hours_worked' => $bill->flat_rate_hours,
                    'total_minutes_worked' => 0,
                    'description' => 'Auto-generated from flat rate bill: ' . $bill->id,
                ]);
            }
        });
        static::updated(function (Bill $bill) {
            if ($bill->is_flat_rate) {
                $time = $bill->times()->first();
                if ($time) {
                    $time->update([
                        'total_hours_worked' => $bill->flat_rate_hours,
                        'description' => 'Updated from flat rate bill: ' . $bill->name,
                    ]);
                } else {
                    $bill->times()->create([
                        'contract_classification_id' => $bill->contract_classification_id,
                        'user_id' => Auth::id(),
                        'date' => now(),
                        'total_hours_worked' => $bill->flat_rate_hours,
                        'total_minutes_worked' => 0,
                        'description' => 'Auto-generated from flat rate bill: ' . $bill->id,
                    ]);
                }
            }
        });
        static::deleted(function (Bill $bill) {
            if ($bill->is_flat_rate) {
                Time::where('description', 'Auto-generated from flat rate bill: ' . $bill->id)
                    ->first()
                    ->delete();
            }
        });
    }

}

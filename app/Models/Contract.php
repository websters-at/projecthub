<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Laravel\Prompts\Note;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "zip_code",
        "customer_id",
        "city",
        "state",
        "country",
        "due_to",
        "address",
        "address2",
        "address3",
        "attachments",
    ];
    public $casts = [
        'attachments' => 'array',
    ];
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'contract_classifications')
            ->withTimestamps();
    }

    public function notes(): HasManyThrough
    {
        return $this->hasManyThrough(
            ContractNote::class,
            ContractClassification::class,
            'contract_id',
            'contract_classification_id',
            'id',
            'id'
        );
    }

    public function bills(): HasManyThrough
    {
        return $this->hasManyThrough(
            Bill::class,
            ContractClassification::class,
            'contract_id',
            'contract_classification_id',
            'id',
            'id'
        );
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function times(): HasManyThrough
    {
        return $this->hasManyThrough(
            Time::class,
            ContractClassification::class,
            'contract_id',
            'contract_classification_id',
            'id',
            'id');
    }

    public function classifications(): HasMany
    {
        return $this->hasMany(ContractClassification::class);
    }

}

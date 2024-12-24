<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        "is_finished",
        "customer_id",
        "city",
        "state",
        "country",
        "due_to",
        "address",
        "address2",
        "address3",
        "priority",
        "attachments",
    ];
    public $casts = [
        'attachments' => 'array',
    ];
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'contract_classifications')
            ->withTimestamps();
    }

    public function notes(): HasMany
    {
        return $this->hasMany(
            ContractNote::class,
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

    public function calls(): HasManyThrough
    {
        return $this->hasManyThrough(
            Call::class,
            ContractClassification::class,
            'contract_id',
            'contract_classification_id',
            'id',
            'id'
        );
    }

    public function login_credentials(): BelongsToMany
    {
        return $this->belongsToMany(
            LoginCredentials::class,
            'contract_login_credentials',
            'contract_id',
            'login_credentials_id'
        );
    }
    public function contract_classifications(): HasMany
    {
        return $this->hasMany(ContractClassification::class);
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class LoginCredentials extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "description",
        "email",
        "contract_login_credentials_id",
        "password",
        "attachments",
    ];
    public $casts = [
        'attachments' => 'array',
    ];


    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(
            Contract::class,
            'contract_login_credentials',
            'login_credentials_id',
            'contract_id'
        );
    }
    public function contract_classification(): HasMany
    {
        return $this->hasMany(ContractClassification::class);
    }

    public function contract_login_credentials(): BelongsTo
    {
        return $this->belongsTo(ContractLoginCredentials::class);
    }


}

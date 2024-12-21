<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractLoginCredentials extends Model
{
    use HasFactory;
    protected $fillable = [
        "contract_login_credentials_id",
        "contract_id"
    ];

    public function contract(): BelongsTo{
        return $this->belongsTo(Contract::class);
    }
    public function login_credentials(): BelongsTo{
        return $this->belongsTo(LoginCredentials::class);
    }
}

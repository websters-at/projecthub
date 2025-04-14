<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        "company_name",
        "address",
        "full_name",
        "email",
        "phone",
        "tax_id",
        "zip_code",
        "city",
        "state",
        "country"
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
    public function calls(): HasMany
    {
        return $this->hasMany(Call::class);
    }
}

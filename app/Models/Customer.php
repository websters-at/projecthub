<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        "company_name",
        "address",
        "zip_code",
        "city",
        "state",
        "country"
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractLoginCredentials extends Model
{
    use HasFactory;
    protected $fillable = [
        "contract_login_credentials_id",
        "contract_id"
    ];
}

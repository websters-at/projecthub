<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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
        "contract_image",
        "orginial_filename"
    ];

    public $casts = [
        'contract_image' => 'array'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'contract_classifications')
            ->withTimestamps();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function times(): HasManyThrough
    {
        return $this->hasManyThrough(Time::class, ContractClassification::class, 'contract_id', 'contract_classification_id', 'id', 'id');
    }

    public function classifications()
    {
        return $this->hasMany(ContractClassification::class);
    }
}

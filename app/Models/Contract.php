<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "zip_code",
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
        return $this
            ->belongsToMany(User::class, 'contract_classifications')
            ->withTimestamps();
    }
    public function customer()
    {
        return $this
            ->belongsTo(Customer::class);

    }
}

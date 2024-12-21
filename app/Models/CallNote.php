<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallNote extends Model
{
    use HasFactory;
    protected $fillable = [
        "call_id",
        "name",
        "description",
    ];

    public function call(): BelongsTo{
        return $this->belongsTo(Call::class);
    }

}

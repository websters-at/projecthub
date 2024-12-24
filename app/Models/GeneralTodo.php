<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneralTodo extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "due_to",
        "description",
        "attachments",
        "user_id",
        "is_done",
        "priority"
    ];

    public $casts = [
        'attachments' => 'array',
    ];
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}

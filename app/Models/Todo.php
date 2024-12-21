<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "due_to",
        "description",
        "attachments",
        "contract_classification_id",
        "is_done"
    ];

}

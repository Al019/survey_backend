<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    use HasFactory;

    protected $table = "options";

    protected $fillable = [
        'question_id',
        'text',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $table = "responses";

    protected $fillable = [
        'respondent_id',
        'question_id',
        'answer',
    ];
}

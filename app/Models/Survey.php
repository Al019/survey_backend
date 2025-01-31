<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    use HasFactory;

    protected $table = "surveys";

    protected $fillable = [
        'admin_id',
        'uuid',
        'title',
        'description',
        'status',
        'published_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function response(): HasMany
    {
        return $this->hasMany(Response::class, 'survey_id');
    }

    public function question(): HasMany
    {
        return $this->hasMany(Question::class, 'survey_id');
    }
}

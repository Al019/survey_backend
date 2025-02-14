<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = "users";

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'gender',
        'email',
        'password',
        'role',
        'status',
        'is_default',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function survey(): HasMany
    {
        return $this->hasMany(Survey::class, 'admin_id');
    }

    public function response(): HasMany
    {
        return $this->hasMany(Response::class, 'enumerator_id');
    }

    public function survey_assignment(): HasMany
    {
        return $this->hasMany(SurveyAssignment::class, 'enumerator_id');
    }
}

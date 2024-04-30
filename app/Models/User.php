<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasRoles, HasApiTokens , HasFactory, Notifiable, InteractsWithMedia;
    public $table = 'users';

    public $fillable = [
        'name',
        'email',
        'calling_code',
        'phone',
        'full_mobile_number',
        'gender',
        'password',
        'date_of_birth',
        'rate',
        'points',
    ];

    protected $appends = ['avatar'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'gender' => 'string',
        'password' => 'string',
    ];

    public static array $rules = [
        'name' => 'required',
        'gender' => 'required',
        'date_of_birth' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed|min:8'
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $edit_rules = [
        'name' => 'required',
        'gender' => 'required',
        'date_of_birth' => 'required',
        'email' => 'required|email|unique:users',
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $apiResetPasswordRules = [
        'code' => 'required',
        'password' => 'required|confirmed|min:6',
    ];

    public function interestCategories()
    {
        return $this->belongsToMany('App\Models\Category', 'users_categories',
            'user_id', 'category_id');
    }

    public function getAvatarAttribute()
    {
        $url = $this->getMedia('user_avatar')->first();
        return $url ? $url->getUrl() : $url;
    }

}

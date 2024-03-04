<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens , HasFactory, Notifiable;
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


    public function interestCategories()
    {
        return $this->belongsToMany('App\Models\Category', 'users_categories',
            'user_id', 'category_id');
    }

}

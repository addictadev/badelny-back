<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PasswordResetsCodes
 * @package App\Models
 * @version April 18, 2023, 11:59 pm UTC
 *
 * @property string $mobile
 * @property string $code
 * @property integer $expired
 * @property string|\Carbon\Carbon $expired_at
 */
class PasswordResetsCodes extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'password_resets_codes';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'mobile',
        'code',
        'email',
        'expired',
        'expired_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'mobile' => 'string',
        'code' => 'string',
        'email' => 'string',
        'expired' => 'integer',
        'expired_at' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mobile' => 'required'
    ];


}

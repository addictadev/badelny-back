<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileVerifications extends Model
{
    public $table = 'mobile_verifications';

    public $fillable = [
        'user_id',
        'calling_code',
        'phone',
        'code',
        'expired',
        'expired_at'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'calling_code' => 'string',
        'phone' => 'string',
        'code' => 'string',
        'expired' => 'integer',
        'expired_at' => 'datetime'
    ];

    public static array $rules = [
        'calling_code' => 'required',
        'phone' => 'required'
    ];


}

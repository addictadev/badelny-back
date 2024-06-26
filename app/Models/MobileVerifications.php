<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileVerifications extends Model
{
    public $table = 'mobile_verifications';

    public $fillable = [
        'user_id',
        'is_user',
        'calling_code',
        'phone',
        'code',
        'expired',
        'is_verification',
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
        'phone' => 'required'
    ];


}

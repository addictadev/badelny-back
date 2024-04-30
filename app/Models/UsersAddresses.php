<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersAddresses extends Model
{
    public $table = 'users_addresses';

    public $fillable = [
        'area_id',
        'address',
        'flat',
        'landmark',
        'phone',
        'user_id',
        'lat',
        'lng',
    ];

    protected $casts = [
        'area_id' => 'integer',
        'address' => 'string',
        'flat' => 'integer',
        'landmark' => 'string',
        'phone' => 'string',
        'user_id' => 'integer'
    ];

    public static array $rules = [
        'area_id' => 'required|exists:areas,id',
        'address' => 'required',
        'flat' => 'required',
        'phone' => 'required'
    ];

    public function area()
    {
        return $this->belongsTo(Areas::class,'area_id');
    }

}

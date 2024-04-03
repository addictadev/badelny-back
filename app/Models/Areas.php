<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    public $table = 'areas';

    public $fillable = [
        'name_en',
        'name_ar',
        'status'
    ];

    protected $appends = ['name'];

    protected $casts = [
        'name_en' => 'string',
        'name_ar' => 'string',
        'status' => 'integer'
    ];

    public static array $rules = [
        'name_en' => 'required',
        'name_ar' => 'required',
        'status' => 'required'
    ];

    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
}

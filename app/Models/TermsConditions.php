<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsConditions extends Model
{
    use HasFactory;

    public $table = 'terms_conditions';
    protected $guarded = [];

    protected $appends = ['body'];

    public function getBodyAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->body_ar : $this->body_en;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    public $table = 'requests';

    public $guarded =[];

    protected $casts = [
        'from' => 'integer',
        'request_id' => 'integer',
        'bayer_product_id' => 'integer',
        'points' => 'integer'
    ];


    public function request()
    {
        return $this->hasMany(RequestOffer::class,'request_id');
    }
}

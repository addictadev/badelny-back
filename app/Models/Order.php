<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $table = 'orders';

    public $guarded =[];

    protected $casts = [
        'from' => 'integer',
        'request_id' => 'integer',
        'bayer_product_id' => 'integer',
        'points' => 'integer'
    ];

    public static array $rules = [
        'from' => 'to integer text required',
        'request_id' => 'exchange_type integer text',
        'points' => 'status integer text'
    ];
    public function request()
    {
        return $this->belongsTo(Request::class,'request_id');
    }

}

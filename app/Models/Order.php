<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $table = 'orders';

    public $fillable = [
        'from',
        'to',
        'exchange_type',
        'buyer_product_id',
        'seller_product_id',
        'points',
        'status',
        'request_id',
    ];

    public function getFillable()
    {
        return $this->fillable;
    }

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

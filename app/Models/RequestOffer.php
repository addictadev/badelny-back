<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestOffer extends Model
{
    public $table = 'request_offers';

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
        'bayer_product_id' => 'seller_product_id integer text',
        'points' => 'status integer text'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class,'request_id');
    }
}

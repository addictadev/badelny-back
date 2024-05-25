<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use  Notifiable;
    public $table = 'orders';

    public $fillable = [
        'from',
        'to',
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

    /* status */
    const STATUS_PENDING                      = 0;
    const STATUS_PROCESSING                   = 1;
    const STATUS_ON_THE_WAY                   = 2;
    const STATUS_DELIVERED                    = 3;

    protected $casts = [
        'from' => 'integer',
        'request_id' => 'integer',
        'points' => 'integer',
        'buyer_product_id' => 'array',
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

    public function OrderStatusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class,'order_id');
    }

}

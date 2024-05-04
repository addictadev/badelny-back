<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Request extends Model
{
    use  Notifiable;
    public $table = 'requests';

    public $fillable = [
        'from',
        'to',
        'buyer_product_id',
        'seller_product_id',
        'points',
        'status',
    ];

    protected $casts = [
        'from' => 'integer',
        'request_id' => 'integer',
        'buyer_product_id' => 'array',
        'points' => 'integer'
    ];


    public function offers()
    {
        return $this->hasMany(RequestOffer::class,'request_id');
    }

}

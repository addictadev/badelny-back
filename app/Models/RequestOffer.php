<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class RequestOffer extends Model
{
    use  Notifiable;
    public $table = 'request_offers';

    public $fillable = [
        'from',
        'to',
        'buyer_product_id',
        'seller_product_id',
        'points',
        'status',
        'request_id',
    ];

    protected $casts = [
        'from' => 'integer',
        'request_id' => 'integer',
        'buyer_product_id' => 'array',
        'points' => 'integer'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class,'request_id');
    }
}

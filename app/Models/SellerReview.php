<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerReview extends Model
{
    use HasFactory;

    public $table = 'sellers_reviews';

    public $fillable = [
        'user_id',
        'seller_id',
        'rate',
    ];
}

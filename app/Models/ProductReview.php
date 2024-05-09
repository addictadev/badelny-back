<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    public $table = 'products_reviews';

    public $fillable = [
        'user_id',
        'product_id',
        'rate',
    ];
}

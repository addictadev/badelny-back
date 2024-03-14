<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    public $table = 'products';

    public $fillable = [
        'name',
        'category_id',
        'sub_category_id',
        'wight',
        'condition',
        'color',
        'exchange_options',
        'price',
        'points',
        'description',
        'status',
        'is_approve',
    ];

    protected $casts = [
        'name' => 'string',
        'wight' => 'string',
        'color' => 'string',
        'price' => 'string',
        'points' => 'string'
    ];

    public static array $rules = [

    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


}

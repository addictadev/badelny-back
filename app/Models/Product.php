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
        'user_id'
    ];

    protected $appends = ['images', 'thumbnail'];

    protected $casts = [
        'name' => 'string',
        'wight' => 'string',
        'color' => 'string',
        'price' => 'string',
        'points' => 'string'
    ];

    public function getThumbnailAttribute()
    {
        $collection = 'products_images';
        $url = $this->getMedia($collection)->first();
        return $url ? $url->getUrl() : $url;
    }

    public function getImagesAttribute()
    {
        $collection = 'products_images';
        $images = $this->getMedia($collection)->toArray();
        return count($images) ? $images : [];
    }

    public static array $rules = [

    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }


}

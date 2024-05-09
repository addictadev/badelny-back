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
        'user_id',
        'exchange_categories',
        'rate'
    ];

    protected $appends = ['images', 'thumbnail'];

    protected $casts = [
        'name' => 'string',
        'wight' => 'string',
        'color' => 'string',
        'price' => 'string',
        'points' => 'string',
        'exchange_categories' => 'array',
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

    public function scopeCategory($query,$category)
    {
        return $query->when($category, function () use ($query, $category) {
            return $query->where('category_id', $category);
        });
    }

        public function scopeSearch($query,$search)
    {
        $query->when($search,function ()use ($query,$search){
            return $query->where('name', 'LIKE', '%' . $search . '%');
        });
    }

}

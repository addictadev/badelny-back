<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;
    public $table = 'categories';

    public $fillable = [
        'name_en',
        'name_ar',
        'has_parent',
        'parent_id'
    ];

    protected $casts = [
        'name_en' => 'string',
        'name_ar' => 'string',
        'parent_id' => 'integer'
    ];

    public static array $rules = [
        'name_ar' => 'required',
        'name_en' => 'required'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'parent_id');
    }


}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id ,
            'name_en' => $this->name_en ,
            'name_ar' => $this->name_ar ,
            'has_parent' => $this->has_parent == 0 ? '0' : [
                'parent' => isset($this->category) ? $this->category->name_en : '' ,
            ],
            'image' => $this->getFirstMediaUrl('images' , 'thumb') ,
            'created_at' => \Carbon\Carbon::parse($this->created_at) ,
            'links' => [
                'self' => url()->current() ,
            ] ,
        ];
    }
}

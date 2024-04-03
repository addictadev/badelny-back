<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name ,
            'user' => $this->user ? $this->user : '',
            'category' => $this->category ? $this->category : '' ,
            'thumbnail' => $this->thumbnail,
            'images' => $this->images,
            'price' => $this->price ,
            'points' => $this->points ,
            'description' => $this->description ,
            'publish' => $this->status ,
            'approve_status' => $this->is_approve ,
            'created_at' => \Carbon\Carbon::parse($this->created_at) ,
            'links' => [
                'self' => url()->current() ,
            ] ,
        ];
    }
}

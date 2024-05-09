<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Favourite;
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

        if(request()->user()){
            $favourite = Favourite::where('product_id',$this->id)->where('user_id',\request()->user()->id)->first();
            $is_favourite = $favourite ? true : false ;
        }else{
        $is_favourite = false;
        }

       if(!empty($this->exchange_categories)){
              $categories = Category::WhereIn('id',$this->exchange_categories)->get();
            }else{
                $categories = null;
            }

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
            'exchange_options' => $this->exchange_options == 1 ? 'All categories' : 'Specific Categories',
            'exchange_categories' =>!is_null($categories) ? CategoryResource::collection($categories) : [],
            'is_favourite' => $is_favourite,
            'rate' => $this->rate,
            'created_at' => \Carbon\Carbon::parse($this->created_at) ,
            'links' => [
                'self' => url()->current() ,
            ] ,
        ];
    }
}

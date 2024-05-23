<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\SellerReview;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use function Symfony\Component\Routing\Loader\Configurator\collection;

class SellerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $posts = Product::where('user_id',$this->id)->get();

        $reviews = SellerReview::where('seller_id',$this->id)->get();

        return [
            'posts' =>  ProductResource::collection($posts),
            'reviews' => SellerReviewResource::collection($reviews),
            'links' => [
                'self' => url()->current(),
            ],
        ];
    }
}

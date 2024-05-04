<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {

        $sellerProduct = Product::find($this->seller_product_id);
        $buyerProducts = Product::WhereIn('id',$this->buyer_product_id)->get();
        $userFrom = User::find($this->from);
        $userTo = User::find($this->to);

        return [
            'id' => $this->id,
            'seller Product' => New ProductResource($sellerProduct),
            'buyer Products' => New ProductResource($buyerProducts),
            'points' => $this->points,
            'from' => new UserResource($userFrom),
            'to' => new UserResource($userTo),
        ];
    }
}

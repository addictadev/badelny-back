<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
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
        if ($this->status == 0){
            $status = 'pending';
        }elseif($this->status == 1){
            $status = 'approved';
        }else{
            $status = 'refused';
        }
        return [

            'id' => $this->id,
            'seller Product' => new ProductResource($sellerProduct),
            'buyer Products' =>  ProductResource::collection($buyerProducts),
            'points' => $this->points,
            'from' => new UserResource($userFrom),
            'to' => new UserResource($userTo),
            'status' => $status,
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format('Y-d-m'),
            'offers' => RequestOfferResource::collection($this->offers),
        ];
    }
}

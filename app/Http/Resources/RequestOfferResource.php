<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestOfferResource extends JsonResource
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
        $user_offer = $buyerProducts->pluck('points')->sum();

        // get total and what seller earn ?

        if (!is_null($this->points))
            // if user send points in request
        {
            $total = $this->points - $user_offer;
        }else
        {
            $total =$sellerProduct->points - $user_offer;
        }
        return [

            'id' => $this->id,
            'seller Product' => new ProductRequestResource($sellerProduct),
            'buyer Products' =>  ProductRequestResource::collection($buyerProducts),
            'points' => $this->points,
            'from' => new UserRequestResource($userFrom),
            'to' => new UserRequestResource($userTo),
            'my_offer' =>$sellerProduct->points,
            'my_nag_offer' =>$this->points,
            'user_offer' => $user_offer,
            'total' => $total,
            'status' => $status  ,
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format('Y-d-m'),

        ];
    }
}

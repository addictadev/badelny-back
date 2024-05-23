<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
           $color = '#FFD700';
       }elseif($this->status == 1){
           $status = 'processing';
           $color =
       }elseif($this->status == 2){
           $status = 'on the way';
       }else{
           $status = 'delivered';
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
            'buyer_Products'=> ProductRequestResource::collection($buyerProducts),
            'points' => $this->points,
            'from' => new UserRequestResource($userFrom),
            'status' => $this->status,
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format('Y-d-m'),
        ];
    }
}

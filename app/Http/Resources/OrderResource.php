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
        $buyerProducts = Product::WhereIn('id',$this->buyer_product_id)->get();
        $userFrom = User::find($this->from);

        return [
            'id' => $this->id,
            'buyer_Products'=> ProductRequestResource::collection($buyerProducts),
            'points' => $this->points,
            'from' => new UserRequestResource($userFrom),
            'status' => $this->getStatusObject($this->status),
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format('Y-d-m'),
        ];
    }


    private function getStatusObject($status)
    {
        $colors = ['#0CB450', '#FFC100', '#dd4b39', '#dd4b39'];
        $texts = [trans('messages.STATUS_PENDING') , trans('messages.STATUS_PROCESSING'), trans('messages.STATUS_ON_THE_WAY'), trans('messages.STATUS_DELIVERED')];
        return [
            'color' => $colors[$status],
            'text' => $texts[$status],
            'value' => $status,
        ];
    }
}

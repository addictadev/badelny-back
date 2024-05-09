<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        $user = User::find($this->user_id);
        $seller = User::find($this->seller_id);
        return [
            'id' => $this->id,
            'user' => new UserResource($user),
            'seller' => new UserResource($seller),
            'rate' => $this->rate,
            'created_at' => \Carbon\Carbon::parse($this->created_at),
            'links' => [
                'self' => url()->current(),
            ],
        ];
    }
}

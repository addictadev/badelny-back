<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {

        return [
                'id' => $this->id,
                'area_id' => $this->area_id,
                'area' => $this->area,
                'address' => $this->address,
                'flat' =>  $this->flat,
                'landmark' => $this->landmark,
                'phone' => $this->phone,
                'created_at' => \Carbon\Carbon::parse($this->created_at)->format('Y-d-m')
        ];
    }
}

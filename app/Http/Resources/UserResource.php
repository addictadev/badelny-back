<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' =>  $this->phone,
                'birth date' => $this->date_of_birth,
                'gender' => $this->gender == 1 ? 'Male' : 'Female',
                'Categories interested' => CategoryResource::collection($this->interestCategories),
                'created_at' => \Carbon\Carbon::parse($this->created_at),
            ],
            'links' => [
                'self' => url()->current(),
            ],
        ];
    }
}

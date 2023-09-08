<?php

namespace App\Http\Resources;

use App\Http\Resources\UserNameResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Withdraw_moneyResource extends JsonResource
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
            'money' => $this->money,
            'Visa_number' => $this->Visa_number,
            'status' => $this->status,
            'transaction_id' => $this->transaction_id,
            'created_at' => $this->created_at,
            'user' => UserNameResource::make($this->whenLoaded('user')),
        ];
    }
}

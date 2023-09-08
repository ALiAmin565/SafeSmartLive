<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BotResources extends JsonResource
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
            'bot_money' => $this->bot_money,
            'image' => $this->image,
            'status' => $this->status,
            'transaction_id'=>$this->transaction_id,
            'userBotTransfer'=>UserNameResource::make($this->whenLoaded('userBotTransfer'))
        ];
    }
}

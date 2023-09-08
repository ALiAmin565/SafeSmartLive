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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'state' => $this->state,
            'phone' => $this->phone,
            'money'=>$this->money,
            'plan' => $this->plan,
            'number_of_user' => $this->number_of_user,
            'affiliate_code'=>$this->affiliate_code,
            'comming_afflite' => $this->comming_afflite,
            'binanceApiKey'=>$this->binanceApiKey,
            'binanceSecretKey'=>$this->binanceSecretKey,
            'Role' => UserNameResource::collection($this->whenLoaded('Role')),
             'bot_transfer'=>BotResources::collection($this->whenLoaded('bot_transfer')),

        ];
    }
}

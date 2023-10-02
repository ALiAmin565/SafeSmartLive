<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserNameResource extends JsonResource
{
    public $namesUsersReceiveMoney;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $getIds=$this->allsendandrecive->pluck('recive_id')->toArray(); 
        $this->namesUsersReceiveMoney=User::whereIn('id',$getIds)->pluck('name')->toArray();
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'money'=>$this->money,
            'banned'=>$this->banned,
            'allsendandrecive'=>$this->namesUsersReceiveMoney,
            'plan'=> $this->plan,  
            'number_of_user'=>$this->number_of_user,
        ];
    }
}

<?php

namespace App\Events;


use App\Models\Massage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use App\Http\Resources\Front\MassageResource;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class ChatPlan implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $massage;
    public $plan;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($massage,$plan)
    {

        $this->massage=$massage;
        // dd($this->plan=$plan);
        $this->plan=$plan;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new Channel('recommendation.'.$this->plan_name);

        // dd('chat.'.$this->plan);
        return new Channel('chat.'.$this->plan);
    }

    public function broadcastAs()
    {
                 // for listen this
                // return 'recommendation.'.$this->plan_name;
            //    dd('chat.'.$this->plan);
        return 'chat.'.$this->plan;
    }


    public function broadcastWith()
    {


        // $media = $this->massage->media;
        // $massage = $this->massage;
        // // dd($massage->id);


        // return  [
        //     'test'=>MassageResource::collection(Massage::with(['user','media'])->find($massage->id))
        //     // 'massage' => $massage,
        //     // 'media' => $media,
        // ];.



    $massage = $this->massage;

    return  [
        'massage' => MassageResource::make(Massage::with(['user','media'])->find($massage->id))
    ];



    }

}

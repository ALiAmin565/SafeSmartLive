<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
// use Illuminate\Http\JsonResponse;

class recommend  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public $plan_name;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data,$plan_name)
    {

        $this->data= $data;

        $this->plan_name = $plan_name;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // for channel
         return new Channel('recommendation.'.$this->plan_name);

        //  dd(150);

    }

    public function broadcastAs()
    {
        // for listen this
        // dd('recommendation.'.$this->plan_name);
         return 'recommendation.'.$this->plan_name;
    }


    public function broadcastWith()
    {


            return [
                'data' => $this->data,
                // 'targets' => $this->target
            ];



                //   return ['event' => response()->json($this->data)];

    }


}

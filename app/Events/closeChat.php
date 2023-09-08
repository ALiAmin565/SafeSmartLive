<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Response;


class closeChat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $plan;
    public $massage;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($plan,$massage)
    {

     $this->plan=$plan;
       $this->massage=$massage;
       
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new Channel('closeChat');
        return new Channel('chat.'.$this->plan);
        // dd( new Channel('closeChat.'.$this->plan));

    }

    public function broadcastAs()
    {
                 // for listen this

       
          return 'chat.'.$this->plan;
    }

    public function broadcastWith()
    {
        
 return [
                'massage' => $this->massage,
                'plan' => $this->plan
            ];


 
 
           



    }

}

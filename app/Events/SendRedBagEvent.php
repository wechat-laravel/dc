<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendRedBagEvent extends Event
{
    use SerializesModels;

    public $action;
    public $open_id;
    public $tasks_id;
    public $offer;
    public $ip;
    public $sex;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($action, $open_id, $tasks_id, $offer, $ip, $sex)
    {
        $this->action = $action;
        $this->open_id = $open_id;
        $this->tasks_id = $tasks_id;
        $this->offer = $offer;
        $this->ip = $ip;
        $this->sex = $sex;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}

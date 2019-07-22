<?php

namespace App\Events;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EmailSyncedWithOutlook implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /** @var string */
    public $broadcastQueue = 'broadcast';

    /** @var \App\Models\User */
    public $user;

    /** @var \Carbon\CarbonImmutable */
    public $date;

    /** @var int */
    public $total;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\User  $user
     * @param  \Carbon\CarbonImmutable  $date
     * @param  int  $total
     */
    public function __construct(User $user, CarbonImmutable $date, int $total)
    {
        $this->user = $user;
        $this->date = $date;
        $this->total = $total;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('outlook');
    }

    /**
     * Name of the Event to broadcast.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'outlookSynced';
    }
}

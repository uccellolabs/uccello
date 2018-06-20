<?php

namespace Sardoj\Uccello\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Http\Request;
use Sardoj\Uccello\Models\Domain;
use Sardoj\Uccello\Models\Module;

class AfterSaveEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $domain;
    public $module;
    public $request;
    public $record;
    public $mode;
    public $isFromApi;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Domain $domain, ?Module $module, ?Request $request, $record, $mode=null, $isFromApi=false)
    {
        $this->domain = $domain;
        $this->module = $module;
        $this->request = $request;
        $this->record = $record;
        $this->mode = $mode;
        $this->isFromApi = $isFromApi;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-record');
    }
}

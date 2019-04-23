<?php

namespace Uccello\Core\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class BeforeDeleteEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $domain;
    public $module;
    public $request;
    public $record;
    public $isFromApi;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Domain $domain, Module $module, Request $request, $record, $isFromApi = false)
    {
        $this->domain = $domain;
        $this->module = $module;
        $this->request = $request;
        $this->record = $record;
        $this->isFromApi = $isFromApi;
    }
}

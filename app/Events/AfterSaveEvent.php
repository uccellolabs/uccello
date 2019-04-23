<?php

namespace Uccello\Core\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

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
    public function __construct(Domain $domain, ?Module $module, ?Request $request, $record, $mode = null, $isFromApi = false)
    {
        $this->domain = $domain;
        $this->module = $module;
        $this->request = $request;
        $this->record = $record;
        $this->mode = $mode;
        $this->isFromApi = $isFromApi;
    }
}

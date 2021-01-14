<?php

namespace Uccello\Core\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as DefaultEventServiceProvider;

/**
 * Event Service Provider
 */
class EventServiceProvider extends DefaultEventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Uccello\Core\Events\AfterSaveEvent::class => [
            \Uccello\Core\Listeners\Core\AfterSaveEventListener::class,
            \Uccello\Core\Listeners\Profile\AfterSaveEventListener::class,
            \Uccello\Core\Listeners\Role\AfterSaveEventListener::class,
            \Uccello\Core\Listeners\User\AfterSaveEventListener::class,
            \Uccello\Core\Listeners\Domain\AfterSaveEventListener::class,
        ],
        \Uccello\Core\Events\AfterDeleteEvent::class => [
            \Uccello\Core\Listeners\Core\AfterDeleteEventListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}

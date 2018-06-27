<?php

namespace Uccello\Core\Providers;

use App\Providers\EventServiceProvider as DefaultEventServiceProvider;
use Illuminate\Support\Facades\Route;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

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
      'Uccello\Core\Events\AfterSaveEvent' => [
          'Uccello\Core\Listeners\Profile\AfterSaveEventListener',
          'Uccello\Core\Listeners\Role\AfterSaveEventListener',
          'Uccello\Core\Listeners\User\AfterSaveEventListener',
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

      //
  }
}
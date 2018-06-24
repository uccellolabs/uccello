<?php

namespace Sardoj\Uccello\Providers;

use App\Providers\EventServiceProvider as DefaultEventServiceProvider;
use Illuminate\Support\Facades\Route;
use Sardoj\Uccello\Models\Domain;
use Sardoj\Uccello\Models\Module;

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
      'Sardoj\Uccello\Events\AfterSaveEvent' => [
          'Sardoj\Uccello\Listeners\Profile\AfterSaveEventListener',
          'Sardoj\Uccello\Listeners\Role\AfterSaveEventListener',
          'Sardoj\Uccello\Listeners\User\AfterSaveEventListener',
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
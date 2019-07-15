<?php

namespace App\Providers;

use App\Listeners\SetEmailCategories;
use Illuminate\Auth\Events\Registered;
use App\Events\EmailSyncedWithOutlook;
use App\Events\Models\Task as TaskEvents;
use App\Listeners\Models\Task as TaskListeners;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /** @var array */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        EmailSyncedWithOutlook::class => [
            SetEmailCategories::class,
        ],
        TaskEvents\Deleting::class => [
            TaskListeners\ClearDueDate::class,
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

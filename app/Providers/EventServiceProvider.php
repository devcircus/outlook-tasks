<?php

namespace App\Providers;

use App\Listeners\SetEmailCategories;
use Illuminate\Auth\Events\Registered;
use App\Events\EmailSyncedWithOutlook;
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

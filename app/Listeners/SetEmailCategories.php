<?php

namespace App\Listeners;

use App\Models\Email;
use App\Events\EmailSyncedWithOutlook;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Category\SetCategoryService;

class SetEmailCategories implements ShouldQueue
{
    use InteractsWithQueue;

    /** @var string */
    public $queue = 'misc';

    /** @var \App\Models\Email */
    private $emails;

    /**
     * Construct a new SetEmailCategories listener.
     *
     * @param  \App\Models\Email  $emails
     */
    public function __construct(Email $emails)
    {
        $this->emails = $emails;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\EmailSyncedWithOutlook  $event
     *
     * @return void
     */
    public function handle(EmailSyncedWithOutlook $event)
    {
        $emails = $this->emails->receivedAfter($event->date)->notCategorized()->forUser($event->user)->get();
        SetCategoryService::call($emails);
    }
}

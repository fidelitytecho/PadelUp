<?php

namespace App\Listeners;

use App\Events\CreateCustomerEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssignCustomerRoleListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  CreateCustomerEvent  $event
     * @return void
     */
    public function handle(CreateCustomerEvent $event)
    {
        $event->createdUser->assignRole('Customer');
    }
}

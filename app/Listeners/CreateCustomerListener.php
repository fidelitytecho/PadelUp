<?php

namespace App\Listeners;

use App\Events\CreateCustomerEvent;
use App\Repositories\Interfaces\CustomerInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateCustomerListener implements ShouldQueue
{
    private $customer;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Handle the event.
     *
     * @param  CreateCustomerEvent  $event
     * @return void
     */
    public function handle(CreateCustomerEvent $event)
    {
        $this->customer->create([
            'user_id' => $event->createdUser->id,
            'company_id' => 1,
        ]);
    }
}

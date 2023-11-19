<?php

namespace App\Listeners;

use App\Services\Mail\SendEmailSyncPrice;

class SendEmail
{
    /**
     * Create the event listener.
     */
    public function __construct(public SendEmailSyncPrice $sendEmailSyncPrice)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if (!empty($event->securityPrices['securityPrices'])) {
            $this->sendEmailSyncPrice->__invoke($event->securityPrices);
        }
    }
}

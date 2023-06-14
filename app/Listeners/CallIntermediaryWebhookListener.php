<?php

namespace App\Listeners;

use App\Events\RequestStatusChangeEvent;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class CallIntermediaryWebhookListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RequestStatusChangeEvent $event): void
    {
        // In this project, we assumed the web hook method to be "POST".
        $client = new Client();
        try {
            $client->post($event->request->webhook(), [
                RequestOptions::JSON => $event->request->toArray()
            ]);
        } catch (\Throwable $e) {
            logger("webhook error: {$e->getMessage()}");
        }
    }
}

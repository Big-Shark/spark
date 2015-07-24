<?php

namespace Laravel\Spark\Http\Controllers\Billing;

use Laravel\Spark\Spark;
use Laravel\Cashier\WebhookController;
use Laravel\Spark\Contracts\Billing\InvoiceNotifier;

class StripeController extends WebhookController
{
    /**
     * Handle a cancelled customer from a Stripe subscription.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleInvoicePaymentSucceeded(array $payload)
    {
        $model = config('auth.model');

        $user = (new $model)->where(
            'stripe_id', $payload['data']['object']['customer']
        )->first();

        if (is_null($user)) {
            return;
        }

        app(InvoiceNotifier::class)->notify(
            $user, $user->findInvoice($payload['data']['object']['id'])
        );
    }
}

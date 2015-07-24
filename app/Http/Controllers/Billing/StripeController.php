<?php

namespace Laravel\Spark\Http\Controllers\Billing;

use Laravel\Spark\Spark;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\WebhookController;
use Illuminate\View\Expression as ViewExpression;

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

        $invoice = $user->findInvoice($payload['data']['object']['id']);

        $invoiceData = array_merge([
            'vendor' => 'Vendor',
            'product' => 'Product',
            'vat' => new ViewExpression(nl2br(e($user->extra_billing_info))),
        ], Spark::generateInvoicesWith());

        Mail::send('spark::emails.billing.invoice', function ($message) use ($user, $invoice, $invoiceData) {
            $message->to($user->email, $user->name)
                    ->subject('Your '.$invoiceData['product'].' Invoice')
                    ->attachData($invoice->pdf($invoiceData), 'invoice.pdf');
        });
    }
}

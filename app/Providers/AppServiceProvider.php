<?php

namespace Laravel\Spark\Providers;

use Laravel\Spark\Spark;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (method_exists($this, 'customizeRegistration')) {
            $this->customizeRegistration();
        }

        if (method_exists($this, 'customizeProfileUpdates')) {
            $this->customizeProfileUpdates();
        }

        if (method_exists($this, 'customizeSubscriptionPlans')) {
            $this->customizeSubscriptionPlans();
        }

        if ($this->twoFactorAuth) {
            Spark::withTwoFactorAuth();
        }

        Spark::generateInvoicesWith($this->invoiceWith);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

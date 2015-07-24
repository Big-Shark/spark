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
        $this->customizeRegistration();

        $this->customizeProfileUpdates();

        $this->customizeSubscriptionPlans();

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

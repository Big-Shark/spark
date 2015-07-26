<?php

namespace Laravel\Spark\Providers;

use Laravel\Spark\Auth\Registrar;
use Laravel\Spark\Auth\Subscriber;
use Laravel\Spark\Console\Install;
use Illuminate\Support\ServiceProvider;
use Laravel\Spark\Billing\EmailInvoiceNotifier;
use Laravel\Spark\Contracts\Billing\InvoiceNotifier;
use Laravel\Spark\Contracts\Auth\Registrar as RegistrarContract;
use Laravel\Spark\Contracts\Auth\Subscriber as SubscriberContract;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->defineRoutes();

        $this->defineResources();
    }

    /**
     * Define the Spark routes.
     *
     * @return void
     */
    protected function defineRoutes()
    {
        if (! $this->app->routesAreCached()) {
            $router = app('router');

            $router->group(['namespace' => 'Laravel\Spark\Http\Controllers'], function ($router) {
                require __DIR__.'/../Http/routes.php';
            });
        }
    }

    /**
     * Define the resources used by Spark.
     *
     * @return void
     */
    protected function defineResources()
    {
        $this->loadViewsFrom(SPARK_PATH.'/resources/views', 'spark');

        $this->publishes([
            SPARK_PATH.'/resources/views' => base_path('resources/views/vendor/spark'),
        ]);

        $this->publishes([
            SPARK_PATH.'/resources/assets' => base_path('resources/assets/vendor/spark'),
        ], 'spark-assets');

        $this->publishes([
            SPARK_PATH.'/resources/views/app.blade.php' => base_path('resources/views/vendor/spark/app.blade.php'),
            SPARK_PATH.'/resources/views/settings/tabs/profile.blade.php' => base_path('resources/views/vendor/spark/settings/tabs/profile.blade.php'),
            SPARK_PATH.'/resources/views/auth/registration/subscription/basics.blade.php' => base_path('resources/views/vendor/spark/auth/registration/subscription/basics.blade.php'),
            SPARK_PATH.'/resources/views/auth/registration/simple/basics.blade.php' => base_path('resources/views/vendor/spark/auth/registration/simple/basics.blade.php'),
        ], 'spark-basics');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (! defined('SPARK_PATH')) {
            define('SPARK_PATH', realpath(__DIR__.'/../../'));
        }

        config([
            'auth.password.email' => 'spark::emails.auth.password'
        ]);

        $this->defineServices();

        $this->commands([
            Install::class
        ]);
    }

    /**
     * Bind the Spark services into the container.
     *
     * @return void
     */
    protected function defineServices()
    {
        $this->app->bindIf(RegistrarContract::class, function () {
            return new Registrar;
        });

        $this->app->bindIf(InvoiceNotifier::class, EmailInvoiceNotifier::class);
    }
}

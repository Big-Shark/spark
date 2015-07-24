<?php

namespace Laravel\Spark\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spark:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Spark scaffolding into the application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->installServiceProviders();
        $this->installMiddleware();
        $this->installRoutes();
        $this->installModels();
        $this->installMigration();
        $this->installViews();
        $this->installSass();
        $this->installEnvironmentVariables();

        $this->table(
            ['Task', 'Status'],
            [
                ['Installing Service Providers', '<info>✔</info>'],
                ['Modifying Provider Configuration', '<info>✔</info>'],
                ['Modifying CSRF Middleware', '<info>✔</info>'],
                ['Modifying Routes', '<info>✔</info>'],
                ['Modifying User Eloquent Model', '<info>✔</info>'],
                ['Modifying User Database Migration', '<info>✔</info>'],
                ['Installing Views', '<info>✔</info>'],
                ['Installing Spark Sass File', '<info>✔</info>'],
                ['Installing Environment Variables', '<info>✔</info>'],
            ]
        );

        if ($this->confirm('Would you like to run Gulp now?')) {
            (new \Symfony\Component\Process\Process('gulp', base_path()))->run();
        }

        $this->displayPostInstallationNotes();
    }

    /**
     * Generate and install the application Spark service provider.
     *
     * @return void
     */
    protected function installServiceProviders()
    {
        copy(
            SPARK_PATH.'/resources/stubs/app/Providers/SparkServiceProvider.php',
            app_path('Providers/SparkServiceProvider.php')
        );

        $config = file_get_contents(config_path('app.php'));

        if ($this->serviceProviderShouldBeAddedToConfig($config)) {
            file_put_contents(
                config_path('app.php'), $this->appendServiceProviderToConfig($config)
            );
        }

        $config = file_get_contents(config_path('app.php'));

        if (! str_contains($config, 'Laravel\Cashier\CashierServiceProvider')) {
            $this->appendCashierServiceProviderToConfig($config);
        }
    }

    /**
     * Determine if the service provider needs to be added to the configuration.
     *
     * @param  string  $config
     * @return bool
     */
    protected function serviceProviderShouldBeAddedToConfig($config)
    {
        return (str_contains($config, 'Laravel\Spark\Providers\SparkServiceProvider::class') &&
            ! str_contains($config, 'App\Providers\SparkServiceProvider::class'));
    }

    /**
     * Append the service provider to the configuration.
     *
     * @param  string  $config
     * @return string
     */
    protected function appendServiceProviderToConfig($config)
    {
        $config = str_replace(
            "SparkServiceProvider::class,\n",
            "SparkServiceProvider::class,\n        App\Providers\SparkServiceProvider::class,\n",
            $config
        );

        return str_replace(
            "SparkServiceProvider::class\n",
            "SparkServiceProvider::class,\n        App\Providers\SparkServiceProvider::class,\n",
            $config
        );
    }

    /**
     * Add the Cashier service provider to the configuration file.
     *
     * @return void
     */
    protected function appendCashierServiceProviderToConfig($config)
    {
        file_put_contents(config_path('app.php'), str_replace(
            "App\Providers\SparkServiceProvider::class,\n",
            "App\Providers\SparkServiceProvider::class,\n        Laravel\Cashier\CashierServiceProvider::class,\n",
            $config
        ));
    }

    /**
     * Install the customized Spark middleware.
     *
     * @return void
     */
    protected function installMiddleware()
    {
        copy(
            SPARK_PATH.'/resources/stubs/app/Http/Middleware/VerifyCsrfToken.php',
            app_path('Http/Middleware/VerifyCsrfToken.php')
        );
    }

    /**
     * Install the routes for the application.
     *
     * @return void
     */
    protected function installRoutes()
    {
        copy(
            SPARK_PATH.'/resources/stubs/app/Http/routes.php',
            app_path('Http/routes.php')
        );
    }

    /**
     * Install the customized Spark models.
     *
     * @return void
     */
    protected function installModels()
    {
        copy(
            SPARK_PATH.'/resources/stubs/app/User.php',
            app_path('User.php')
        );
    }

    /**
     * Install the user migration file.
     *
     * @return void
     */
    protected function installMigration()
    {
        copy(
            SPARK_PATH.'/resources/stubs/database/migrations/2014_10_12_000000_create_users_table.php',
            database_path('migrations/2014_10_12_000000_create_users_table.php')
        );
    }

    /**
     * Install the default views for the application.
     *
     * @return void
     */
    protected function installViews()
    {
        copy(
            SPARK_PATH.'/resources/views/home.blade.php',
            base_path('resources/views/home.blade.php')
        );
    }

    /**
     * Install the default Sass file for the application.
     *
     * @return void
     */
    protected function installSass()
    {
        copy(
            SPARK_PATH.'/resources/stubs/resources/assets/sass/app.scss',
            base_path('resources/assets/sass/app.scss')
        );
    }

    /**
     * Install the environment variables for the application.
     *
     * @return void
     */
    protected function installEnvironmentVariables()
    {
        $env = file_get_contents(base_path('.env'));

        if (str_contains($env, 'AUTHY_KEY=')) {
            return;
        }

        (new Filesystem)->append(
                    base_path('.env'),
                    PHP_EOL.'AUTHY_KEY='.PHP_EOL.PHP_EOL.
                    'STRIPE_KEY='.PHP_EOL.
                    'STRIPE_SECRET='.PHP_EOL
                );
    }

    /**
     * Display the post-installation information to the user.
     *
     * @return void
     */
    protected function displayPostInstallationNotes()
    {
        $this->comment(PHP_EOL.'Post Installation Notes:');

        $this->line(PHP_EOL.'     → Set <info>AUTHY_KEY</info>, <info>STRIPE_KEY</info>, & <info>STRIPE_SECRET</info> Environment Variables');
    }
}

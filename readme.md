# Laravel Spark

- [Installation](#installation)
- [Defining Subscription Plans](#defining-subscription-plans)
- [Teams](#teams)
- [Exporting Spark Views](#exporting-spark-views)

<a name="installation"></a>
## Installation

First, install the Spark installer. Make sure that the global Composer `bin` directory is within your system's `$PATH`:

	composer global require "laravel/spark-installer=~1.0"

Next, create a new Laravel application and install Spark:

	laravel new application

	cd application

	spark install

After installing Spark, be sure to migrate your database, install the NPM dependencies, and run the `gulp` command. You should also set the `AUTHY_KEY`, `STRIPE_KEY`, and `STRIPE_SECRET` environment variables in your `.env` file.

You may also wish to review the `SparkServiceProvider` class that was installed in your application. This provider is the central location for customizing your Spark installation.

<a name="defining-subscription-plans"></a>
## Defining Subscription Plans

Subscription plans may be defined in your `app/Providers/SparkServiceProvider.php` file. This file contains a `customizeSubscriptionPlans` method. Within this method, you may define all of your application's subscription plans. There are a few examples in the method to get you started.

### Coupons

To use a coupon, simply create the coupon on Stripe and access the `/register` route with a `coupon` query string variable that matches the ID of the coupon on Stripe.

	http://stripe.app/register?coupon=code

Site-wide promotions may be run using the `Spark::promotion` method within your `SparkServiceProvider`:

	Spark::promotion('coupon-code');

<a name="teams"></a>
## Teams

To enable teams, simply use the `CanJoinTeams` trait on your `User` model. The trait has already been imported in the top of the file, so you only need to add it to the model itself:

	class User extends Model implements TwoFactorAuthenticatableContract,
	                                    BillableContract,
	                                    CanResetPasswordContract
	{
	    use Billable, CanJoinTeams, CanResetPassword, TwoFactorAuthenticatable;
	}

<a name="exporting-spark-views"></a>
## Exporting Spark Views

You may publish Spark's Blade views by using the `vendor:publish` command:

	php artisan vendor:publish --tag=spark-basics

The published views will be placed in `resources/views/vendor/spark`.

# Laravel Spark

- [Installation](#installation)

<a name="installation"></a>
## Installation

First, install the Spark installer. Make sure that the global Composer `bin` directory is within your system's `$PATH`:

	composer global require "laravel/spark-installer=~1.0"

Next, create a new Laravel application and install Spark:

	laravel new application

	cd application

	spark install

After installation, you may set the `AUTHY_KEY`, `STRIPE_KEY`, and `STRIPE_SECRET` environment variables in your `.env` file.

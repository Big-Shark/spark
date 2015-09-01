
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

require('laravel-spark/core/dependencies');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

require('laravel-spark/settings/dashboard/profile')
require('laravel-spark/settings/dashboard/security/password')
require('laravel-spark/settings/dashboard/security/two-factor')

require('laravel-spark/settings/team/owner')
require('laravel-spark/settings/team/membership/edit-team-member')

if ($('#spark-app').length > 0) {
	new Vue(require('laravel-spark'));
}

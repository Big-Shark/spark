var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.browserify('./spark.js', './dist/spark.js');
});

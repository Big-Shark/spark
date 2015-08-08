window.Vue = require('vue');
window._ = require('underscore');
window.moment = require('moment');

window.$ = window.jQuery = require('jquery');
require('bootstrap-sass/assets/javascripts/bootstrap');

// Configure Vue...
require('vue-resource');
Vue.http.headers.common['X-CSRF-TOKEN'] = CSRF_TOKEN;

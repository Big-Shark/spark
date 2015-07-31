<?php

// Application Routes...
$router->get('terms', 'AppController@showTerms');

// Settings Routes...
$router->get('settings', 'SettingsController@showDashboard');

// Profile Routes...
$router->put('settings/user', 'SettingsController@updateUserProfile');

// Security Routes...
$router->put('settings/user/password', 'SettingsController@updatePassword');
$router->post('settings/user/two-factor', 'SettingsController@enableTwoFactorAuth');
$router->delete('settings/user/two-factor', 'SettingsController@disableTwoFactorAuth');

// Subscription Routes...
$router->post('settings/user/plan', 'SettingsController@subscribe');
$router->put('settings/user/plan', 'SettingsController@changeSubscriptionPlan');
$router->delete('settings/user/plan', 'SettingsController@cancelSubscription');
$router->post('settings/user/plan/resume', 'SettingsController@resumeSubscription');
$router->put('settings/user/card', 'SettingsController@updateCard');
$router->put('settings/user/vat', 'SettingsController@updateExtraBillingInfo');
$router->get('settings/user/plan/invoice/{id}', 'SettingsController@downloadInvoice');

// Authentication Routes...
$router->get('login', 'Auth\AuthController@getLogin');
$router->post('login', 'Auth\AuthController@postLogin');
$router->get('logout', 'Auth\AuthController@getLogout');

// TWo-Factor Authentication Routes...
$router->get('login/token', 'Auth\AuthController@getToken');
$router->post('login/token', 'Auth\AuthController@postToken');

// Registration Routes...
$router->get('register', 'Auth\AuthController@getRegister');
$router->post('register', 'Auth\AuthController@postRegister');

// Password Routes...
$router->get('password/email', 'Auth\PasswordController@getEmail');
$router->post('password/email', 'Auth\PasswordController@postEmail');
$router->get('password/reset/{token}', 'Auth\PasswordController@getReset');
$router->post('password/reset', 'Auth\PasswordController@postReset');

// API Routes...
$router->get('spark/api/user', 'ApiController@getCurrentUser');
$router->get('spark/api/plans', 'ApiController@getPlans');
$router->get('spark/api/coupon/{code}', 'ApiController@getCoupon');
$router->get('spark/api/user/coupon', 'ApiController@getCouponForUser');

// Stripe Routes...
$router->post('stripe/webhook', 'Stripe\WebhookController@handleWebhook');

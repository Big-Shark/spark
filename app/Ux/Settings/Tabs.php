<?php

namespace Laravel\Spark\Ux\Settings;

class Tabs
{
    /**
     * The settings tabs configuration.
     *
     * @var array
     */
    public $tabs = [];

    /**
     * Create a new settings tabs instance.
     *
     * @param  array  $tabs
     * @return void
     */
    public function __construct(array $tabs = [])
    {
        $this->tabs = $tabs;
    }

    /**
     * Define the settings tabs configuration.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function configure(callable $callback)
    {
        $this->tabs = array_filter(call_user_func($callback, $this));

        return $this;
    }

    /**
     * Get the tab configuration for the "profile" tab.
     *
     * @return array
     */
    public function profile()
    {
        return new Tab('Profile', 'spark::settings.tabs.profile', 'fa-user');
    }

    /**
     * Get the tab configuration for the "security" tab.
     *
     * @return array
     */
    public function security()
    {
        return new Tab('Security', 'spark::settings.tabs.security', 'fa-lock');
    }

    /**
     * Get the tab configuration for the "subscription" tab.
     *
     * @return array
     */
    public function subscription()
    {
        return new Tab('Subscription', 'spark::settings.tabs.subscription', 'fa-credit-card');
    }

    /**
     * Create a new custom tab instance.
     *
     * @param  string  $name
     * @param  string  $view
     * @param  string  $icon
     * @return \Laravel\Spark\Ux\Settings\Tab
     */
    public function make($name, $view, $icon)
    {
        return new Tab($name, $view, $icon);
    }
}

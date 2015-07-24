<?php

namespace Laravel\Spark\Ux\Settings;

class Tab
{
    /**
     * The tab key.
     *
     * @var string
     */
    public $key;

    /**
     * The tag's displayable name.
     *
     * @var string
     */
    public $name;

    /**
     * The view contents of the tab.
     *
     * @var string
     */
    public $view;

    /**
     * The FontAwesome icon for the tab.
     *
     * @var string
     */
    public $icon;

    /**
     * Create a new tab instance.
     *
     * @param  string  $name
     * @param  string  $view
     * @param  string  $icon
     * @return void
     */
    public function __construct($name, $view, $icon)
    {
        $this->name = $name;
        $this->view = $view;
        $this->icon = $icon;
        $this->key = str_slug($this->name);
    }
}

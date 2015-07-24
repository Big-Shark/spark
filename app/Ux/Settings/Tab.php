<?php

namespace Laravel\Spark\Ux\Settings;

class Tab {

	public $key;

	public $name;

	public $view;

	public $icon;

	public function __construct($name, $view, $icon)
	{
		$this->name = $name;
		$this->view = $view;
		$this->icon = $icon;
		$this->key = str_slug($this->name);
	}

}

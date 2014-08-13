<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller_SuperController {

	public function action_index($arg1 = false, $arg2 = false)
	{
	    $this->js[] = '/js/common.js';
	    $this->js[] = '/js/manager.js';
	    $this->js[] = '/js/showtime.js';
	    $this->css[] = '/css/form.css';
		$this->content = View::factory('manager');
		$this->side = View::factory('side');
	}

} // End Welcome

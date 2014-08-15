<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Status extends Controller_SuperController {
    public function after(){
        $this->mainView = View::factory('settings');
        parent::after();
    }
    public function before(){
        $this->config['showtime'] = Kohana::config('showtime');
    }
    public function action_index(){
        $this->content = View::factory('status/statuslist');
        $this->js[] = '/js/statuslist.js';
        $this->content->devices = Model::factory('showtime')->get_devices();
    }
} // End Welcome

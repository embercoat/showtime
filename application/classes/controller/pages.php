<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pages extends Controller {
    protected $content;
    protected $config;
    public function after(){
        $this->response->body($this->content);
    }
    public function before(){
        $this->config['showtime'] = Kohana::config('showtime');
    }
    public function action_get_assets(){
        $r = DB::select('*')->from('assets')->order_by('name', 'ASC')->execute()->as_array();
        $this->content = json_encode($r);
    }
    public function action_black(){
        $this->content = View::factory('black_page');
    }
    public function action_splash(){
        $this->content = View::factory('splash');
        $r = DB::select('*')->from('devices')->where('address', '=', $_SERVER['REMOTE_ADDR'])->execute()->as_array();
        if(count($r)){
            $data =  $r[0];
        } else {
            DB::insert('devices', array('name', 'address'))->values(array($_SERVER['REMOTE_ADDR'], $_SERVER['REMOTE_ADDR']))->execute();
            $data = DB::select('*')->from('devices')->where('address', '=', $_SERVER['REMOTE_ADDR'])->execute()->as_array();
        }
        $this->content->data = $data;

    }

} // End Welcome

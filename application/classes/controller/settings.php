<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Settings extends Controller_SuperController {
    protected $content;
    protected $config;
    public function after(){
        $this->mainView = View::factory('settings');
        parent::after();
    }
    public function before(){
        $this->config['showtime'] = Kohana::config('showtime');
    }
    public function action_playlist_devices(){
        $list = array();
        $playlists = array();
        $this->js[] = '/js/showtime.js';
        $this->js[] = '/js/settings.js';
	    $this->js[] = '/js/common.js';
        foreach(model::factory('showtime')->get_playlists() as $pl) $playlists[$pl['idplaylist']] = $pl;

        $this->content = view::factory('settings/playlist_devices');

        $this->content->playlists = $playlists;
        $this->content->devices = array();
        foreach(model::factory('showtime')->get_devices() as $device){
            $device_playlists = model::factory('showtime')->get_device_playlists($device['device_id']);
            $this->content->devices[$device['device_id']] = array_merge($device, array('playlists' => array_pop($device_playlists)));
        }
    }
    public function action_delete_asset($asset_id){
        if(isset($_POST) && !empty($_POST)){
            model::factory('showtime')->delete_asset($asset_id);
            header('location: /');
            die();
        }
        $this->content = View::factory('settings/delete_asset');
        $this->content->uses = Model::factory('showtime')->get_asset_use($asset_id);
        list($this->content->asset) = model::factory('showtime')->get_assets($asset_id);
    }
} // End Welcome

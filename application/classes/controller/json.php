<?php defined('SYSPATH') or die('No direct script access.');

class Controller_json extends Controller {
    protected $content;
    protected $config;


    public function after(){
        $this->response->body($this->content);
    }
    public function before(){
        $this->config['showtime'] = Kohana::config('showtime');
    }

    
    public function action_get_device_status($device_id){
        $address = Model::factory('showtime')->get_device_address($device_id);
        if($address){
            try {
                $socket = Model::factory('socket')->set('host', $address)->set('port', 10000);
                $socket->connect();
                $socket->write('status');
                $json = $socket->read();
                $status = json_decode($json, true);
                $all_good = true;
                foreach($status as $proc => $running)
                    if(!$running)
                        $all_good = false;
                
                echo json_encode(array('Device_id' => $device_id, 'Status' => ($all_good ? 'OK' : 'Problem'), 'processes' => $status));
            }
            catch(Exception $error){
                echo json_encode(array('Device_id' => $device_id, "Status" => "Error", "Error" => $error->getMessage()));
            }
        }
    }
    
    public function action_get_assets(){
        $this->content = json_encode(model::factory('showtime')->get_assets());
    }

    public function action_get_playlists($id = false){
        $this->content = json_encode(model::factory('showtime')->get_playlists($id));
    }

    public function action_save_playlist(){
        model::factory('showtime')->save_playlist($_POST);
    }

    public function action_playlist_schedule_remove($schedule_id){
        model::factory('showtime')->playlist_schedule_remove($schedule_id);
    }

    public function action_get_playlist_assets($id){
        $this->content = json_encode(model::Factory('showtime')->get_playlist_assets($id));
    }
    
    public function action_delete_asset($id){
        model::factory('showtime')->delete_asset($id);
    }
    
    public function action_save_asset(){
        model::factory('showtime')->save_asset($_POST, $_FILES);
    }
    
    public function action_playlist_add_asset(){
        model::Factory('showtime')->playlist_add_asset($_POST);
    }
    
    public function action_playlist_remove_asset(){
        model::Factory('showtime')->playlist_remove_asset($_POST);
    }
    
    public function action_playlist_update_assets(){
        if(isset($_POST[0])){
            model::Factory('showtime')->playlist_update_assets($_POST);
        }
    }
    
    public function action_update_playlist_asset_duration(){
        if(isset($_POST)){
            model::Factory('showtime')->playlist_update_playlist_asset_duration($_POST);
        }
    }
    
    public function action_get_playlist_schedule($playlist){
        $this->content = json_encode(model::Factory('showtime')->get_playlist_schedule($playlist));
    }
    
    public function action_set_playlist_schedule_attribute(){
        model::Factory('showtime')->set_playlist_schedule_attribute($_POST);
    }
    
    public function action_add_playlist_schedule_time(){
        model::Factory('showtime')->add_playlist_schedule_time($_POST['playlist_id']);
    }
    
    public function action_playlist_remove($playlist){
        model::Factory('showtime')->playlist_remove($playlist);
    }
    
    public function action_get_device_id(){
        $this->content = model::Factory('showtime')->get_device_id($_SERVER['REMOTE_ADDR']);
    }
    
    public function action_get_playlist_modtime($playlist){
        $this->content = model::Factory('showtime')->get_playlist_modtime($playlist);
    }
    
    public function action_get_active_playlist($device_id){
        $playlist = $this->get_active_playlist($device_id);
        $this->content = $playlist['idplaylist'];
    }
    
    function get_active_playlist($device_id){
        return model::Factory('showtime')->get_active_playlist($device_id);
    }
    
    public function action_client_assetlist($device_id){
        $this->content = json_encode(model::Factory('showtime')->client_assetlist($device_id));
    }
    
    public function action_get_devices(){
        $this->content = json_encode(model::Factory('showtime')->get_devices());
    }
    
    public function action_device_update_name(){
        model::Factory('showtime')->device_update_name($_POST);
    }
    
    public function action_device_add_playlist(){
        model::Factory('showtime')->device_add_playlist($_POST);
    }
    
    public function action_device_remove_playlist(){
        model::Factory('showtime')->device_remove_playlist($_POST);
    }
    
    public function action_get_device_playlists($device){
        $this->content = json_encode(model::Factory('showtime')->get_device_playlists($device));
    }
} // End Welcome

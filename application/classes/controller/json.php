<?php defined('SYSPATH') or die('No direct script access.');

class Controller_json extends Controller {
    protected $content;
    protected $config;


    public function after(){
        $this->response->body($this->content);
    }
    public function before(){
        $this->config['screener'] = Kohana::config('screener');
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
        //$this->content = json_encode(DB::select('*')->from('playlist_schedule')->where('playlist_id', '=', $playlist)->execute()->as_array());
    }
    public function action_add_playlist_schedule_time(){
        model::Factory('showtime')->add_playlist_schedule_time($_POST['playlist_id']);
/*        DB::insert('playlist_schedule', array('playlist_id', 'starttime', 'startdayofweek', 'endtime', 'enddayofweek', 'priority'))
            ->values(array(
                $_POST['playlist_id'], 0,0,0,0,0
            ))
            ->execute();
           */
    }
    public function action_playlist_remove($playlist){
        model::Factory('showtime')->playlist_remove($playlist);
        /*DB::delete('playlist_assets')->where('playlist', '=', $playlist)->execute();
        DB::delete('playlists')->where('idplaylist', '=', $playlist)->execute();
        DB::delete('playlist_schedule')->where('playlist_id', '=', $playlist)->execute();*/
    }
    public function action_get_device_id(){
        $this->content = model::Factory('showtime')->get_device_id($_SERVER['REMOTE_ADDR']);
        /*$r = DB::select('*')->from('devices')->where('address', '=', $_SERVER['REMOTE_ADDR'])->execute()->as_array();
        if(count($r)){
            $this->content =  $r[0]['device_id'];
        } else {
            $id = DB::insert('devices', array('name', 'address'))->values(array($_SERVER['REMOTE_ADDR'], $_SERVER['REMOTE_ADDR']))->execute();
            $this->content = $id[0];
        }*/
    }
    public function action_get_playlist_modtime($playlist){
        $this->content = model::Factory('showtime')->get_playlist_modtime($playlist);
/*        list($modtime) = DB::select('last_alter')->from('playlists')->where('idplaylist', '=', $playlist)->execute()->as_array();
        $this->content = $modtime['last_alter'];*/
    }
    public function action_get_active_playlist($device_id){
//        $this->content = model::Factory('showtime')->get_active_playlist($device_id);
        $playlist = $this->get_active_playlist($device_id);
        $this->content = $playlist['idplaylist'];

    }
    function get_active_playlist($device_id){
        return model::Factory('showtime')->get_active_playlist($device_id);
        /*$sql = DB::select_array(array('playlists.*', 'playlist_schedule.*'))
        ->from('playlist_devices')
        ->join('playlists')
        ->on('playlist_devices.playlist', '=', 'playlists.idplaylist')
        ->join('playlist_schedule')
        ->on('playlists.idplaylist', '=', 'playlist_schedule.playlist_id')
        ->where('playlist_devices.device', '=', $device_id)
        ->where(DB::expr(date('NHi')), 'between', DB::expr('CONCAT(startdayofweek,starttime) and CONCAT(enddayofweek, endtime)'))
        ->order_by('priority', 'DESC');
        $playlists = $sql->execute()->as_array();
        if(count($playlists) == 0){
            $sql = DB::select_array(array('playlists.*', 'playlist_schedule.*'))
            ->from('playlist_devices')
            ->join('playlists')
            ->on('playlist_devices.playlist', '=', 'playlists.idplaylist')
            ->join('playlist_schedule')
            ->on('playlists.idplaylist', '=', 'playlist_schedule.playlist_id')
            ->where('default', '=', '1')
            ->order_by('priority', 'DESC');
            $playlists = $sql->execute()->as_array();
        }
        $playlist = $playlists[0];
        return $playlist;*/
    }
    public function action_client_assetlist($device_id){
        $this->content = json_encode(model::Factory('showtime')->client_assetlist($device_id));
        /*$playlist = $this->get_active_playlist($device_id);
        $playlist_id = $playlist['idplaylist'];

        $sql = DB::select_array(array('assets.*', 'playlist_assets.sortorder', 'playlist_assets.duration'))
                    ->from('assets')
                    ->join('playlist_assets')
                    ->on('playlist_assets.asset', '=', 'assets.id')
                    ->where('playlist_assets.playlist', '=', $playlist_id);
        $assets = $sql->execute()
                    ->as_array();
        $playlist_endtime['hour'] = substr($playlist['endtime'], 0, 2);
        $playlist_endtime['minute'] = substr($playlist['endtime'], 2, 2);

        $playlist_starttime['hour'] = substr($playlist['starttime'], 0, 2);
        $playlist_starttime['minute'] = substr($playlist['starttime'], 2, 2);
        $assetlist  = array();

        $end_timestamp = time()+(($playlist['enddayofweek']-date('N'))*86400);
        $start_timestamp = time()-((date('N')-$playlist['startdayofweek']-1)*86400);

        foreach($assets as $a){
            $assetlist[] = array(
                    'startdate'  => mktime($playlist_starttime['hour'], $playlist_starttime['minute'], null, date('n', $start_timestamp), date('j', $start_timestamp)),
                    'mimetype'   => $a['mimetype'],
                    'name'       => $a['name'],
                    'end_date'   => mktime($playlist_endtime['hour'], $playlist_endtime['minute'], null, date('n', $end_timestamp), date('j', $end_timestamp)),
                    'uri'        => $a['uri'],
                    'duration'   => $a['duration'],
                    'play_order' => $a['sortorder'],
                );
        }
        $this->content = json_encode($assetlist);*/
    }
    public function action_get_devices(){
        $this->content = json_encode(model::Factory('showtime')->get_devices());
        //$this->content = json_encode(DB::select('*')->from('devices')->order_by('name', 'ASC')->execute()->as_array());
    }
    public function action_device_update_name(){
        model::Factory('showtime')->device_update_name($_POST);
        //DB::update('devices')->set(array('name' => $_POST['name']))->where('device_id', '=', $_POST['device'])->execute();
    }
    public function action_device_add_playlist(){
        model::Factory('showtime')->device_add_playlist($_POST);
        /*$r = DB::select('*')->from('playlist_devices')->where('playlist', '=', $_POST['playlist'])->where('device', '=', $_POST['device'])->execute()->as_array();
        if(count($r) == 0){
            DB::insert('playlist_devices', array('playlist', 'device'))
                ->values(
                    array(
                        'playlist' => $_POST['playlist'],
                        'device' => $_POST['device']))
                ->execute();
        }*/
    }
    public function action_device_remove_playlist(){
        model::Factory('showtime')->device_remove_playlist($_POST);
        //DB::delete('playlist_devices')->where('playlist', '=', $_POST['playlist'])->where('device', '=', $_POST['device'])->execute();
    }
    public function action_get_device_playlists($device){
        $this->content = json_encode(model::Factory('showtime')->get_device_playlists($device));
        /*$r = DB::select('*')
                ->from('playlist_devices')
                ->where('device', '=', $device)
                ->execute()->as_array();
        $return = array();
        foreach($r as $row){
            $return[$row['device']][$row['playlist']] = 1;
        }
        $this->content = json_encode($return);*/
    }
} // End Welcome

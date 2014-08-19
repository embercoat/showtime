<?php
class model_showtime extends Model {
    protected $config;
    
    public function model_showtime(){
        $this->config['showtime'] = Kohana::config('showtime');
    }

    public function get_assets($id = false){
        $sql = DB::select('*')->from('assets')->order_by('name', 'ASC');
        if($id){
            $sql->where('id', '=', $id);
        }

        return $sql->execute()->as_array();
    }

    public function get_playlists($id = false){
        $sql = DB::select('*')->from('playlists');
        if($id){
            $sql->where('idplaylist', '=', $id);
        }
        return $sql->execute()->as_array();
    }

    public function save_playlist($data){
        $id = $data['playlist_id'];
        unset($data['playlist_id']);
        $vals = array();
        foreach($data as $k => $val)
            $vals[ str_replace('playlist_', '', $k)] = $val;
        if($id == ''){
            DB::insert('playlists', array_keys($vals))->values($vals)->execute();
        } elseif(is_numeric($id)){
            DB::update('playlists')->set($vals)->where('idplaylist', '=', $id)->execute();
        }
    }

    public function playlist_schedule_remove($schedule_id){
        DB::delete('playlist_schedule')->where('playlist_schedule_id', '=', $schedule_id)->execute();
    }

    public function get_playlist_assets($id){
        $r = DB::select('*')
        ->from('assets')
        ->join('playlist_assets')
        ->on('playlist_assets.asset', '=', 'assets.id')
        ->where('playlist_assets.playlist', '=', $id)
        ->order_by('playlist_assets.sortorder', 'ASC')
        ->execute()
        ->as_array();
        return $r;
    }

    public function delete_asset($id){
        DB::delete('assets')->where('id', '=', $id)->execute();
        DB::delete('playlist_assets')->where('asset', '=', $id)->execute();
        $sql = DB::select_array(array('uri', 'mimetype'))
        ->from('assets')
        ->where('assets.id', '=', $id);
        $r = $sql->execute()->as_array();

        if(count($r) && strlen($r['uri']) == 32){
            unlink($this->config['showtime']['assetpath'].$uri);
        }

    }

    public function save_asset($post, $file){
        var_dump($post);
        if(array_search($post['asset_mimetype'], array('webpage', 'rtmp', 'livestream')) !== FALSE){
            $uri = $post['asset_uri'];
        } else {
            $uri = md5_file($file['asset_file']['tmp_name']);
            move_uploaded_file($file['asset_file']['tmp_name'], $this->config['showtime']['assetpath'].$uri);
        }
        $id = $post['asset_id'];
        unset($post['asset_id']);
        if($id == ''){
            DB::insert('assets', array('name', 'uri', 'mimetype', 'truemime'))
            ->values(array(
                    $post['asset_name'],
                    $uri,
                    $post['asset_mimetype'],
                    $file['asset_file']['type']
            ))
            ->execute();
        } elseif(is_numeric($id)) {
            DB::update('assets')
            ->set(array(
                    'name'     => $post['asset_name'],
                    'uri'      => $uri,
                    'mimetype' => $post['mimetype'],
                    'mimetype' => $file['asset_file']['type']
            ))
            ->where('id', '=', $id)
            ->execute();
        }
    }

    public function playlist_add_asset($post){
        $exist = DB::select('*')
        ->from('playlist_assets')
        ->where('playlist', '=', $post['playlist'])
        ->where('asset', '=', $post['asset'])
        ->order_by('sortorder', 'DESC')
        ->limit('1')
        ->execute()
        ->as_array();
        if(count($exist) == 0){
            $sortorder = DB::select('*')
            ->from('playlist_assets')
            ->where('playlist', '=', $post['playlist'])
            ->order_by('sortorder', 'DESC')
            ->limit('1')
            ->execute()
            ->as_array();
            if(count($sortorder) == 0) $neworder = 1;
            else $neworder = $sortorder[0]['sortorder']+1;

            DB::insert('playlist_assets', array('playlist', 'asset', 'sortorder'))->values(array($post['playlist'], $post['asset'], $neworder))->execute();
        } else {
        }

    }

    public function playlist_remove_asset($post){
        DB::delete('playlist_assets')->where('playlist', '=', $post['playlist'])->where('asset', '=', $post['asset'])->execute();
    }

    public function playlist_update_assets(){
        DB::delete('playlist_assets')->where('playlist', '=', $post[0]['playlist'])->execute();
        foreach($post as $p)
            DB::insert('playlist_assets', array('playlist', 'asset', 'sortorder', 'duration'))->values($p)->execute();
    }

    public function playlist_update_playlist_asset_duration($post){
        DB::update('playlist_assets')
        ->set(array('duration' => $post['duration']))
        ->where('playlist', '=', $post['playlist'])
        ->where('asset', '=', $post['asset'])
        ->execute();
    }

    public function get_playlist_schedule($playlist){
        return DB::select('*')->from('playlist_schedule')->where('playlist_id', '=', $playlist)->execute()->as_array();
    }

    public function set_playlist_schedule_attribute($post){
        DB::update('playlist_schedule')
        ->set(array(
                $post['attribute'] => $post['value']
        ))
        ->where('playlist_schedule_id', '=', $post['schedule_id'])
        ->execute();
    }

    public function add_playlist_schedule_time($pid){
        DB::insert('playlist_schedule', array('playlist_id', 'starttime', 'startdayofweek', 'endtime', 'enddayofweek', 'priority'))
        ->values(array(
                $pid, 0,0,0,0,0
        ))
        ->execute();
    }

    public function playlist_remove($playlist){
        DB::delete('playlist_assets')->where('playlist', '=', $playlist)->execute();
        DB::delete('playlists')->where('idplaylist', '=', $playlist)->execute();
        DB::delete('playlist_schedule')->where('playlist_id', '=', $playlist)->execute();
    }

    public function get_device_id($addr){
        $r = DB::select('*')->from('devices')->where('address', '=', $addr)->execute()->as_array();
        if(count($r)){
            return $r[0]['device_id'];
        } else {
            $id = DB::insert('devices', array('name', 'address'))->values(array($addr, $addr))->execute();
            return $id[0];
        }
    }
    public function get_device_address($device_id){
        $r = DB::select('address')->from('devices')->where('device_id', '=', $device_id)->execute()->as_array();
        if(count($r)){
            return $r[0]['address'];
        } else {
            return false;
        }
    }
    public function get_playlist_modtime($playlist){
        list($modtime) = DB::select('last_alter')->from('playlists')->where('idplaylist', '=', $playlist)->execute()->as_array();
        return $modtime['last_alter'];
    }
/*    public function get_active_playlist($device_id){
        $playlist = $this->get_active_playlist($device_id);
        return $playlist['idplaylist'];
    }*/

    function get_active_playlist($device_id){
        $sql = DB::select_array(array('playlists.*', 'playlist_schedule.*'))
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
        return $playlist;
    }

    public function client_assetlist($device_id){
        $playlist = $this->get_active_playlist($device_id);
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
        return $assetlist;
    }

    public function get_devices(){
        return DB::select('*')->from('devices')->order_by('name', 'ASC')->execute()->as_array();
    }

    public function device_update_name($post){
        DB::update('devices')->set(array('name' => $post['name']))->where('device_id', '=', $post['device'])->execute();
    }
    public function device_add_playlist($post){
        $r = DB::select('*')->from('playlist_devices')->where('playlist', '=', $post['playlist'])->where('device', '=', $post['device'])->execute()->as_array();
        if(count($r) == 0){
            DB::insert('playlist_devices', array('playlist', 'device'))
            ->values(
                    array(
                            'playlist' => $post['playlist'],
                            'device' => $post['device']))
                            ->execute();
        }
    }

    public function device_remove_playlist($post){
        DB::delete('playlist_devices')->where('playlist', '=', $post['playlist'])->where('device', '=', $post['device'])->execute();
    }

    public function get_device_playlists($device){
        $r = DB::select('*')
        ->from('playlist_devices')
        ->where('device', '=', $device)
        ->execute()->as_array();
        $return = array();
        foreach($r as $row){
            $return[$row['device']][$row['playlist']] = 1;
        }
        return $return;
    }
    public function get_asset_use($aid){
        return DB::select('*')
                ->from('playlist_assets')
                ->join('playlists')
                ->on('playlist_assets.playlist', '=', 'playlists.idplaylist')
                ->where('playlist_assets.asset', '=', $aid)
                ->execute()->as_array();
    }
}
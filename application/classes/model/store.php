<?php
class model_store extends Model {
    function set($thing, $value){
        $exist = DB::select('key')
                    ->from('store')
                    ->where('key', '=', $thing)
                    ->execute()
                    ->as_array();
        if(count($exist) > 0){
            DB::update('store')
                ->set(array('value' =>$value))
                ->where('key', '=', $thing)
                ->execute();
        } else {
            DB::insert('store', array('key', 'value'))
                ->values(array($thing, $value))
                ->execute();
        }
    }
    function set_to_now($thing){
        $this->set($thing, time());
    }
    function get($thing){
        $get = DB::select('*')
            ->from('store')
            ->where('key', '=', $thing)
            ->execute()
            ->as_array();
        if(count($get) > 0){
            return $get[0];
        } else {
            return false;
        }
    }
    function get_value($thing){
        $get = DB::select('value')
            ->from('store')
            ->where('key', '=', $thing)
            ->execute()
            ->as_array();
        if(count($get) > 0){
            return $get[0]['value'];
        } else {
            return false;
        }
    }
}
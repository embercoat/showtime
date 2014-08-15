<?php
class controller_file extends Controller{
    protected $config;
/*    public function after(){
        $this->response->body($this->content);
    }*/
    public function before(){
        $this->config['showtime'] = Kohana::config('showtime');
    }
    function action_get($file){
        //list($file, $null) = explode('.', $file);
        if(is_file($this->config['screener']['assetpath'].$file)){
            list($mime) = DB::select('truemime')->from('assets')->where('uri', '=', $file)->execute()->as_array();

            $this->response->send_file($this->config['screener']['assetpath'].$file, null, array('inline' => true));
        }


    }
}
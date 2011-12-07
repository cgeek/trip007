<?php

class Home extends CI_Controller {


    private $_data = array();

    public function index($flush = NULL)
    {
        $data = array();   
        $dir = BASEPATH .'../data/cache';
        if (!file_exists($dir))
        {
            @mkdir($dir,0777);
        }
        $filename = $dir ."/trip007.json";
        $lastflush = @filemtime($filename);
        if (!file_exists($filename) || ($lastflush+(3 *60) <= time()) || !empty($flush))
        {
            date_default_timezone_set ('Asia/Shanghai');
            $url = 'http://api.t.sina.com.cn/statuses/user_timeline/1776438131.json?source=2437693526&feature=1';
            $json_data = file_get_contents($url);
		
            $feed_list = json_decode($json_data,TRUE);
            foreach($feed_list as $feed) {
                if (empty($feed['thumbnail_pic']))
                    $feed['thumbnail_pic'] = "/static/images/default-feed.jpeg";
                $feed['text'] = $this->_process_content($feed['text']);
                $feed['created_at'] = date('Y-m-d H:i:s',strtotime($feed['created_at']));
                
                $data[] = $feed;
            }

            file_put_contents($filename,json_encode($data));

        } 
        else 
        {
            $data = json_decode(file_get_contents($filename),TRUE);
        }
        $this->_data['feed_list'] = $data;
        $this->load->view('home',$this->_data);
    }

    private function _process_content($content) {
        $reg = '/http:\/\/[-a-zA-Z0-9@:%_\+.~#?&\/]+/i';       
        $content = preg_replace($reg, 
            '<a href="\0" target=_blank rel=nofollow>\0</a>', $content);
        
        $reg2 = '/#[\w\W]+#/i';
        preg_match('/#([\w\W]+)#/',$content,$matches);
        if ( ! empty($matches[1])) {
            $content = preg_replace($reg2,'<a href="http://s.weibo.com/weibo/'.$matches[1] .'">'.$matches[1].' :  </a>',$content);
        }
        return $content;
    }

}

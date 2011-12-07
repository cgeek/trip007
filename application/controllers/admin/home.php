<?php


class Home extends MY_Controller {

    //public function __construct() {
     //   parent:__construct();
   // }

    public function index()
    {
        $this->load->library('auth');
        $this->load->view('home');
    }
}

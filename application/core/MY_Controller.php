<?php

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('auth');
        
        if ( ! $this->auth->hasLogin()) {
            redirect('/login?ref=');
        }

        $this->load->library('admin');

    }
}


<?php

class Admin {

    private $_admin = array();

    public $uid = 0;

    public $username = '';
    
    public $email = '';

    public $group = 1;

    public $logintime = 0;

    public $loginip ='';

    public $logincount ='';

    public $token = '';

    public $activated = 0;

    private $_CI;

    public function __construct()
    {
        $this->_CI = & get_instance();

        $this->_admin = unserialize($this->_CI->session->userdata('admin'));
        
        if(!empty($this->_admin)) {
            $this->uid = $this->_admin['uid'];
            $this->username = $this->_admin['username'];
            $this->email = $this->_admin['email'];
            $this->group = $this->_admin['group'];
            $this->logintime = $this->_admin['logintime'];
            $this->loginip = $this->_admin['loginip'];
            $this->logincount = $this->_admin['logincount'];
            $this->token = $this->_admin['token'];
            $this->activated = $this->_admin['activated'];
        }
    }

}

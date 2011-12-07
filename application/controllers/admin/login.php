<?php

/**
 * 登录
 */
class Login extends CI_Controller {

    /**
     * 传到view的数据
     *
     * @access private
     * @var array
     */
    private $_data;

    /**
     * Referer
     * @access public 
     * @var string
     */
    public $referer;

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() 
    {
        parent::__construct();

        $this->load->library('auth');
        $this->load->library('form_validation');
        
        $this->load->model('admin_model','admin');
        
        $this->_check_referer();

        $this->_data['page_title'] = '登录';
    }

    /**
     * 获取跳转referer
     * @access private
     * @return void
     */
    private function _check_referer()
    {
        $ref = $this->input->get('ref',TRUE);
        $this->referer = empty($ref) ?'/home' : trim($ref);
    }

    /**
     * 登录页面
     *
     * @access public
     * @return void
     */
    public function index()
    {
        if ($this->auth->hasLogin()) 
        {
            redirect($this->referer);
        }
        $this->form_validation->set_rules('username','用户名','required');
        $this->form_validation->set_rules('password','密码','required');
        $this->form_validation->set_message('required','%s不能为空');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('login');
        } else {
            $this->_login();
        }
    }

    /**
     * 验证登录
     *
     * @access private
     * @return void;
     */
    private function _login() {
        $username = $this->input->post('username',TRUE);
        $password = $this->input->post('password',TRUE);

        $admin = $this->admin->validate_admin($username,$password);
        if (! empty($admin)) {
            if($this->auth->process_login($admin)) {
                redirect($this->referer);
            }
        } else {
            echo $username . '   ' .$password;
        }
    }

    /**
     * 用户登出
     * 
     * @access public 
     * @return void
     */
    public function logout()
    {
        $this->auth->process_logout();
    }
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 控制用户登录和登出，简单的ACL控制
 *
 * @package admin
 * @subpackage Libraries
 * @category Libraries
 * @author   cgeek <cgeek.share@gmail.com>
 */
class Auth 
{
    /**
     * 用户
     *
     * @access private
     * @var array
     */
    private $_admin = array();

    /**
     * 是否已经登录
     *
     * @access private
     * @var    boolean
     */
    private $_hasLogin = NULL;

    /**
     * 用户组
     *
     * @access public
     * @var array
     */
    public $groups = array(
        'super' => 0, 
        'admin' => 1,
        'editor' => 2
    );

    /**
     * CI 句柄
     *
     * @access private
     * @var object
     */
    private $_CI;

    public function __construct()
    {
        $this->_CI = & get_instance();
        $this->_CI->load->model('admin_model');

        $this->_admin = unserialize($this->_CI->session->userdata('admin'));
    }

    /**
     * 判断是否已经登录
     * @access public
     * @return boolean
     */
    public function hasLogin() 
    {
        if (NULL !== $this->_hasLogin) {
            return $this->_hasLogin;
        } else {
            if ( !empty($this->_admin) && NULL !== $this->_admin['uid']) {
                $admin = $this->_CI->admin_model->get_admin_by_id($this->_admin['uid']);
                if ($admin && $admin['token'] === $this->_admin['token']) {
                    $admin['activated'] = time();

                    $this->_CI->admin_model->update_admin($this->_admin['uid'],$admin);
                    return ($this->_hasLogin = TRUE);

                }
            }
            return ($this->_hasLogin = FALSE);
        }
    }

    /**
     * 退出登录
     *
     * @access public
     * @return void
     */
    public function process_logout()
    {
        $this->_CI->session->sess_destroy();

        redirect('login');
    }

    /**
     * 登录处理函数
     *
     * @access public
     * return boolean
     */
    public function process_login($admin)
    {
        $this->_admin = $admin;
        $this->_admin['last'] = time();

        $this->_set_session();
        $this->_hasLogin = TRUE;
        return TRUE;
    }

    /**
     * 设置session
     * 
     * @access private
     * @return void
     */
    private function _set_session()
    {
        $session_data = array('admin'=>serialize($this->_admin));
        $this->_CI->session->set_userdata($session_data);
    }

}

/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */

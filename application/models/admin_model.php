<?php

/**
 * 后台管理用户model
 *
 * @package qiugonglue
 * @author cgeek <cgeek.share@gmail.com>
 */
class Admin_model extends CI_Model {
    
    private $table_admin = 'admin';

    /**
     * 根据id获取admin信息
     *
     * @access public
     * @return array
     */
    public function get_admin_by_id($uid)
    {
        $data = array();
        $sql = "SELECT * FROM ". $this->table_admin . " WHERE uid='$uid' LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() == 1) {
            $data = $query->row_array();
        }
        $query->free_result();

        return $data;
    }

    /**
     * 根据uid更新admin信息
     *
     * @access public
     * @return boolean
     */
    public function update_admin($uid,$data)
    {
        $this->db->where('uid',intval($uid));
        $this->db->update($this->table_admin,$data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * 验证密码是否正确
     *
     * @access public 
     * @param string username
     * @param string password
     * @return boolean
     */
    public function validate_admin($username,$password)
    {
        $data = FALSE;
        $sql = "SELECT * FROM ". $this->table_admin ." WHERE username='$username' LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() == 1) {
            $data = $query->row_array();
        }
        if (!empty($data)) {
            $data = (md5($password) === $data['password']) ? $data : FALSE;
        }
        $query->free_result();
        return $data;
    }

}

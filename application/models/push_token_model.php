<?php

class Push_token_model extends CI_Model {

    //表名
    var $PUSH_TOKEN_TABLE = 'push_token';

    /**
     * 插入数据库
     *
     * @access public
     * @param array $data
     * @return void
     */
    public function insert_token($data) 
    {
        $this->db->insert($this->PUSH_TOKEN_TABLE,$data);
    }


    /**
     *  根据uuid获取token
     *
     *  @access public
     *  @param var $uuid
     *  @return array
     */
    public function get_token_by_uuid($uuid)
    {
        $sql = "SELECT * FROM $this->PUSH_TOKEN_TABLE WHERE uuid='$uuid' LIMIT 1";
        $query =$this->db->query($sql);
        if ($query->num_rows() >0) {
            return $query->result_array();
        }else {
            return NULL;
        }
    }
    
    /**
     * 更新push token
     *
     * @access public
     * @param varchar uuid
     * @param varchar token
     * @return boolean
     */
    public function update_token($uuid,$token)
    {
        $sql = "UPDATE $this->PUSH_TOKEN_TABLE SET push_token = '$token' WHERE uuid='$uuid'";
        $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

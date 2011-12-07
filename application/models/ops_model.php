<?php

class Ops_model extends CI_Model {
	var $id;
	var $title = "";
	var $author ="";
	
	function __construct() 
	{
		parent::__construct();
	}

    /**
     * 插入新的攻略ops表
     *
     * @access public
     * @param array $data
     * @return int id
     */
	function insert_ops($data) {
		if (isset($data['title'])) {
			$this->db->insert('OPS',$data);
			return $this->db->insert_id();
		} else {
			return 0;
		}
		
	}

    /**
     * 更新ops表操作
     *
     * @access public
     * @param int $id
     * @param array $data
     * @return Boolean
     */
	public function update_ops($ops_id, $data) {
        if ($ops_id <= 0 ) {
            return FALSE;
        }
        $sql = 'UPDATE OPS SET author = \'' . $data['author'] .'\', title = \'' . $data['title'] . '\' WHERE id = ' . $ops_id;
        $this->db->query($sql);
        return ($this->db->affected_rows() >= 0)?TRUE:FALSE;
	}

    /**
     * 根据唯一id获取攻略信息
     *
     * @access public
     * @param  $id
     * @return array 
     */
	public function get_ops_by_id($id) {
		$sql = "SELECT * FROM OPS WHERE id=$id LIMIT 1";
		$query = $this->db->query($sql);
		if ($query->num_rows > 0) {
			return $query->row_array();
        } else {
            return NULL;
        }
	}
}

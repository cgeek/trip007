<?php

class Poi_model extends CI_Model {
	
	 var $id			= '';
	 var $address	= '';
	 var $area 		= '';
	 var $image_thumb= '';
	 var $lat 		= '';
	 var $lon		= '';
	 var $brief		= '';
	 var $title 		= '';
	
	function __construct()
	{
		parent::__construct();
	}

     /**
      * 插入新的poi到数据库
      *
      * @access public 
      * @param  array $data
      * @return int result
      */
    public function insert_poi($data)
	{
		if ( ! empty($data)) {
			$this->db->insert('poi',$data);			
			return $this->db->insert_id();
		} else {
			return 0;
		}	
	}
	
	function get_poi($id)
	{
		$sql = "SELECT * FROM poi WHERE id=$id LIMIT 1";
		$query = $this->db->query($sql);
		
		return $query->row_array();
	}

    /**
     * 根据poi—list-id获取poi列表
     *
     * @access public 
     * @param int $poi_list_id
     * @return array
     */
	public function get_pois_poi_list_id($poi_list_id)
	{
		$sql = "SELECT * FROM poi WHERE poi_list_id=$poi_list_id";
		$query = $this->db->query($sql);
		$result = array();
		if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return NULL;
        }
	}
	
}

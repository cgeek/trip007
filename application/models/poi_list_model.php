<?php

class Poi_list_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

    /**
     * 根据section id获取poi_list列表
     *
     * @access public
     * @param int $section_id
     * @return array
     */
	public function get_poi_list_by_section_id($section_id)
	{
		$sql = "SELECT * FROM poi_list WHERE section_id=$section_id";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$result = array();
			foreach($query->result_array() as $row) {
				$result[] = $row;
			}
			return $result;
		} else {
			return NULL;
		}
	}

    /**
     * 插入新的poi_list
     *
     * @access public
     * @param array
     * @return int
     */
	public function insert($data)
	{
		if ( ! empty($data)) {
			$this->db->insert('poi_list',$data);
			return $this->db->insert_id();	
		} else {
			return 0;
		}
		
    }


    public function get_poi_list_ext_section_by_id($poi_list_id)
    {
        $sql = "SELECT a.*,b.title as section_title,b.type FROM poi_list a LEFT JOIN section b ON (a.section_id =b.id) WHERE a.id=$poi_list_id LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() >0 ) {
            return $query->row_array();
        } else {
            return NULL;
        }
    }
}

<?php

class Section_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}

    /**
     * 根据ops_id获取sections
     *
     * @access public
     * @param int $ops_id
     * @return array
     */
	public function get_sections_by_opsid($id)
	{
		$sql = 'SELECT * FROM section WHERE ops_id=' .$id . ' ORDER BY orderid ASC';
		$query = $this->db->query($sql);
		$result = array();
		if ($query->num_rows() > 0) {
		   	foreach ($query->result_array() as $row) {
                $section = array(
                    'id'    => $row['id'],
					'title' => $row['title'],
					'icon_file' => $row['icon_file'],
					'url'	=>	'/'.$row['type'] .'.json',
                    'num_popup'	=>	$row['num_popup'],
                    'type'  => $row['type']
				);
		    	$result[] = $section;
		   	}
		}
		return $result;
	}

    /**
     * 根据主键id获取section
     *
     * @access public 
     * @param  int $id
     * return  array
     */
	public function get_section_by_id($id)
	{
		$sql = "SELECT * FROM section WHERE id=$id LIMIT 1";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0 ) {
			return $query->row_array();
		} else {
			return NULL;
		}
    }

    /**
     * 插入新的section
     *
     * @access public
     * @return int id
     */
    public function insert_section($data) 
    {
        if (! empty($data)) {
            $this->db->insert('section',$data);
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }
}

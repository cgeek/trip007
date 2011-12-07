<?php
class Story_model extends CI_Model {
    
    function __construct() 
	{
		parent::__construct();	
	}


    /**
     * 添加新的story，插入数据库
     * 
     * @access public
     * @return num_rows
     */
	public function insert_story($data) 
	{
		if ( ! empty($data)) {
			$this->db->insert('story',$data);
			return $this->db->insert_id();	
		} else {
			return 0;
		}
	}

    public function update_story($story_id, $data)
    {
        if ($story_id <0 ) {
            return FALSE;
        }

        $sql = 'UPDATE story SET title=\''.$data['title'].'\', brief =\''.$data['brief'].'\', story=\'' .$data['story'] .'\'  WHERE id = ' . $story_id;
        $this->db->query($sql);

        return TRUE;
    }

    /**
     * 根据id获取story
     *
     * @access public
     * @param  int $id
     * @return array
     */
	public function get_story_by_id($id) 
	{
		$sql = "SELECT * FROM story WHERE id=$id LIMIT 1";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return NULL;
		}
	}
	
	function get_storys_by_sectionid($id) {
		$sql = "SELECT * FROM story WHERE section_id = $id";
		$query = $this->db->query($sql);
		
		$result = array();
		if ($query->num_rows() > 0 ) {
			foreach($query->result_array() as $row) {
				$result[] = $row;
			}
		}
		return $result;
	}
	
}

<?php

class Images_model extends CI_Model {
	
	function __construct() 
	{
		parent::__construct();
	}
	
	function update_image($data)
	{
		$sql = "SELECT * FROM image WHERE image_id='$data[image_id]' LIMIT 1";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$update = "UPDATE image SET title='$data[title]' WHERE image_id='$data[image_id]'";
			$this->db->query($update);
		} else {
			$this->db->insert('image',$data);			
		}

	}
}
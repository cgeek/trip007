<?php

class User_model extends CI_Model {
	
	var $id = "";
	var $username = "";
	var $email = "";
	var $screen_name = "";
	var $province = 0;
	var $city = 0;
	var $location = "";
	var $description;
	var $profile_image_url;
	var $gender;
	var $from;
	var $ext_uid;
	
	function __construct() 
	{
		parent::__construct();	
	}
	
	function get_user_by_ext_uid($uid)
	{
		$query = $this->db->query("SELECT * FROM users WHERE ext_uid=$uid LIMIT 1");
		if ($query->num_rows() > 0) {
			return $query->row_array();
		}
	}
	
	function insert_user_from_ext($ext_user,$from) {
		$user = array();
		$user['username'] = $ext_user['username'];
		$user['email'] = $ext_user['email'];
		$user['screen_name'] = $ext_user['screen_name'];
		$user['province'] = $ext_user['email'];
		$user['city'] = $ext_user['city'];
		$user['location'] = $ext_user['location'];
		$user['description'] = $ext_user['description'];
		$user['profile_image_url'] = $ext_user['profile_image_url'];
		$user['gender'] = $ext_user['gender'];
		$user['from'] = $from;
		$user['ext_uid'] = $ext_user['ext_uid'];
		
		$this->db->insert('users',$user);
		
	}
}
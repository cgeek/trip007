<?php

class Feedback_model extends CI_Model {
	
	var $id;
	var $content = '';
	var $uuid = '';
	var $client_version = '';
	var $create_time;
	var $lat = '';
	var $lon = '';
	
	function __construct() {
		parent::__construct();
	}
	
	public function insert_feedback($data) {
		if ( !empty($data)) {
		 	$this->db->insert('feedback',$data);			
		}
	}
}
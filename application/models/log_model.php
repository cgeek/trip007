<?php

class Log_model extends CI_Model {
	var $id;
	var $uuid = '';
	var $log_format_version = '1.0';
	var $lat = '';
	var $lon = '';
	var $url = '';
	var $timestamp;
	
	public function insert_log($data) {
		$log_data = array();
		$log_data['uuid'] = $data['uuid'];
		$log_data['log_format_version'] = $data['log_format_version'];
		$log_data['lat'] = $data['lat'];
		$log_data['lon'] = $data['lon'];
		$log_data['url'] = $data['url'];
		$log_data['timestamp'] = $data['timestamp'];
		
		$this->db->insert('logs',$log_data);
	}
}
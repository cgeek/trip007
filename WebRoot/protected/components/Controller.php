<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	public function ajax_response($success=true,$message="",$data = array())
	{
		$result['success'] = $success;
		$result['message'] = $message;
		$result['data'] = $data;
		echo json_encode($result);
		exit();
	}

}

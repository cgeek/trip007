<?php
Yii::import('ext.upyun.UpYun',true);

class ImageController extends Controller
{
	private $_data;
	
	private $upyun;

	public function actionUpload()
	{
		$image = file_get_contents('php://input');
		if(empty($image))
		{
			$this->ajax_response(false,'图片为空');
		}
		$image_id = md5($image);
		$imgObj = imagecreatefromstring($image);
		$width = imagesx($imgObj);
		$height = imagesy($imgObj);
		$image_info = array('image_id' => $image_id, 'image_url_s' => upimage($image_id,'small'),'image_url_b' => upimage($image_id,'big'),'width' => $width, 'height' => $height);
		$this->_save2upyun($image_id,$image);
		$this->_data['image'] = $image_info;
		$this->ajax_response(true,'',$this->_data);
	}

	private function _save2upyun($image_id,$image_string)
	{
		$this->upyun = new Upyun();
		$status = $this->upyun->writeFile('/'. $image_id, $image_string);
		if(FALSE === $status)
		{
			Yii::log("image upload to upyun error, image_id: $image_id", 'error');
		}
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	 */
}

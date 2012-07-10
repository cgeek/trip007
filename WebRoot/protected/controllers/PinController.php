<?php

class PinController extends Controller
{
	private $_data;
	
	private $_waterfall_pic_width = 222; //瀑布流图片宽度

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('detail','welcomehot','index','hotAjax','top'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('add','edit','savepinAjax', 'deleteAjax'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$limit = 10;
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=0");
		$criteria->order = ' `ctime` DESC';
		$criteria->limit = $limit;
		$count = Pin::model()->count($criteria);
		$data = Pin::model()->findAll($criteria);
		$pin_list = array();
		foreach($data as $pin)
		{
			$pin_list[] = $this->_format_pin($pin);
		}
		$this->_data['pin_list'] = $pin_list;
		//$this->ajax_response(true,'',$this->_data);
		
		$this->render('index',$this->_data);
	}

	public function actionTop()
	{
		$this->render('index',$this->_data);
	}

	public function actionHotAjax()
	{
		$p = intval($_GET['p']) > 1 ? intval($_GET['p']) : 1;

		$per_page = 10;
		$offset = ($p - 1) * $per_page;
		$limit = $per_page; 
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=0");
		$criteria->order = ' `ctime` DESC';
		$criteria->limit = $limit;
		$criteria->offset = $offset;
		$count = Pin::model()->count($criteria);
		$data = Pin::model()->findAll($criteria);
		$pin_list = array();
		foreach($data as $pin)
		{
			$pin_list[] = $this->_format_pin($pin);
		}
		$this->_data['pin_list'] = $pin_list;
		$this->ajax_response(true,'',$this->_data);
	}

	public function actionAdd()
	{
		$this->render('/pin/edit_pin',$this->_data);
	}

	public function actionEdit($id)
	{
		$pin_db = Pin::model()->findByPk($id);
		if(empty($pin_db))
			throw new CHttpException(404,'Not found');
		$this->_check_pin_author($pin_db);
		$this->_data['pin_db'] = $pin_db;
		$this->render('/pin/edit_pin',$this->_data);
	}

	private function _check_pin_author($pin_db)
	{
		if(Yii::app()->user->isGuest || $pin_db['user_id'] != Yii::app()->user->user_id)
			throw new CHttpException(500,'Can not Access');
		return true;
	}

	public function actionSavePinAjax()
	{
		$data['title'] = htmlspecialchars(mysql_escape_string(trim($_POST['title'])));
		$data['content'] = htmlspecialchars(mysql_escape_string($_POST['content'])); 
		$data['desc'] = htmlspecialchars(mysql_escape_string($_POST['desc'])); 
		$data['cover_image'] = $_POST['cover_image_id'];
		$data['cover_image_width'] = $_POST['cover_image_width'];
		$data['cover_image_height'] = $_POST['cover_image_height'];

		$pin_id = $_POST['pin_id'];
		if(isset($pin_id) && $pin_id > 0) {
			$this->_save_edit_pin($pin_id,$data);
		} else {
			$this->_save_add_pin($data);
		}
	}
	
	private function _save_edit_pin($pin_id,$data)
	{
		$pin_db = Pin::model()->findByPk($pin_id);
		if(empty($pin_db))
			throw new CHttpException(404,'Not found');
		$this->_check_pin_author($pin_db);

		Pin::model()->updateByPk($pin_id, $data);
		$this->_data['pin_id'] = $pin_id;
		$this->ajax_response(true,'',$this->_data);
	}

	private function _save_add_pin($data)
	{
		$new_pin = new Pin;
		$new_pin->title = $data['title'];
		$new_pin->content = $data['content'];
		$new_pin->desc = $data['desc'];
		$new_pin->cover_image = $data['cover_image'];
		$new_pin->cover_image_width = $data['cover_image_width'];
		$new_pin->cover_image_height = $data['cover_image_height'];
		$new_pin->user_id = Yii::app()->user->user_id;
		$new_pin->ctime = time();
	
		if($new_pin->save())
		{
			$new_pin_id = $new_pin->pin_id;
			// 更新用户发表数量
			$this->_update_user_stats();
			$this->_data['pin_id'] = $new_pin_id;
			$this->ajax_response(true,'',$this->_data);
		} else {
			$this->ajax_response(false,'插入失败');
		}
	}

	public function actionDeleteAjax()
	{
		$pin_id = $_POST['pin_id'];
		if(empty($pin_id) || $pin_id <=0)
			$this->ajax_response(false,'参数不正确');
		$pin_db = Pin::model()->findByPk($pin_id);
		if(empty($pin_db))
			$this->ajax_response(false,'该信息不存在');
		$this->_check_pin_author($pin_db);

		$data = array('status'=> -1);
		Pin::model()->updateByPk($pin_id, $data);
		// 更新用户发表数量
		$this->_update_user_stats();
		$this->ajax_response(true,'删除成功');
	}

	public function actionDetail()
	{
		$pin_id =  $_GET['id'];
		if(empty($pin_id) || $pin_id <= 0)
			throw new CHttpException(404,'Not found');

		$pin = Pin::model()->find("pin_id=:pin_id",array(":pin_id"=>$pin_id));
		
		if(empty($pin) || empty($pin['user_id']))
			throw new CHttpException(404,'Not found');
		//update view_count
		Pin::model()->updateByPk($pin_id, array('view_count'=> $pin['view_count'] +1));
		$pinner = User::model()->findByPk($pin['user_id']);

		if(!Yii::app()->user->isGuest && Yii::app()->user->user_id == $pin['user_id']) {
			$this->_data['is_author'] = true;
		}

		$this->_data['pin'] = $pin;
		$this->_data['pinner'] = $pinner;
		$this->render('detail',$this->_data);
	}

	private function _update_user_stats()
	{
		$user_id = Yii::app()->user->user_id;
		$pin_count = Pin::model()->count("user_id=:user_id AND status=0",array("user_id"=>$user_id));
		User::model()->updateByPk($user_id, array('pin_count'=>$pin_count));
	}

	public function actionWelcomeHot()
	{
		$p = $_GET['p'];
		if($p > 2) {
			$this->ajax_response(true,'','');
		}
		$url = 'http://api.t.sina.com.cn/statuses/user_timeline/2636967831.json?source=2437693526&feature=1';
		$json_data = file_get_contents($url);
		$feed_list = json_decode($json_data,TRUE);
		$data = array();
		foreach($feed_list as $feed) {
			if (empty($feed['thumbnail_pic']))
				$feed['thumbnail_pic'] = "/static/images/default-feed.jpeg";
			$feed['text'] = $this->_process_content($feed['text']);
			$feed['created_at'] = date('Y-m-d H:i:s',strtotime($feed['created_at']));
			$data[] = $feed;
		}
		$this->_data = json_encode($data);
		$this->ajax_response(true,'', $this->_data);
	}

	private function _process_content($content) {
		$reg = '/http:\/\/[-a-zA-Z0-9@:%_\+.~#?&\/]+/i';
		$content = preg_replace($reg,'<a href="\0" target=_blank rel=nofollow>\0</a>', $content);

		$reg2 = '/#[\w\W]+#/i';
		preg_match('/#([\w\W]+)#/',$content,$matches);
		if (!empty($matches[1])) {
			$content = preg_replace($reg2,'<a target="_blank" href="http://s.weibo.com/weibo/'.$matches[1] .'">'.$matches[1].' :  </a>',$content);
		}
		return $content;
	}

	private function _format_pin($pin)
	{
		$format_pin = array();
		$user = User::model()->findByPk($pin->user_id);

		$format_pin['pin_id'] = $pin->pin_id;
		$format_pin['title'] = $pin->title;
		$format_pin['desc'] = empty($pin->desc) ? mb_substr(strip_tags(htmlspecialchars_decode($pin->content)),0,200,'utf-8') : htmlspecialchars_decode($pin->desc);
		$format_pin['cover_image_b'] = upimage($pin->cover_image,'big');
		$format_pin['cover_image_m'] = upimage($pin->cover_image,'medium');
		$format_pin['cover_image_mw'] = upimage($pin->cover_image,'mw');
		$fixed_width = fixed_pin_height($pin->cover_image_width, $pin->cover_image_height);
		$format_pin['cover_image_width'] = $fixed_width['width'];
		$format_pin['cover_image_height'] = $fixed_width['height'];
		$format_pin['user'] = array('user_id'=> $user->user_id,'user_name'=>$user->user_name,'avatar'=>$user->avatar);
		$format_pin['ctime'] = date("Y-m-d H:i",$pin->ctime);
		return $format_pin;
	}
}

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
				'actions'=>array('index','detail','search','tejia','gonglue','listAjax','top'),
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

	public function actionSearch($keyword = NULL , $type = NULL)
	{
		if($type == 'tejia') {
			$type_id = 1;
		} else if($type == 'gonglue') {
			$type_id = 2;
		} else {
			throw new CHttpException(404,'Not found');
		}
		if(empty($keyword)) 
			throw new CHttpException(404,'Not found');

		$pin_list = array();
		$query = $keyword . ' type:'.$type_id;
		$docs = Yii::app()->search->setQuery($query)->search(); 
		foreach($docs as $doc)
		{
			$pin = Pin::model()->findByPk($doc->pin_id);
			$pin_list[] = $this->_format_pin($pin);
		}
		$this->_data['pin_list'] = $pin_list;
		$this->_data['waterfall_api_url'] = "/Api/Pin.search?type=$type_id&keyword=$keyword";
		$this->pageTitle = "$keyword - 特价信息 - 旅游特价情报站";
		$this->render($type ,$this->_data);
	}


	public function actionIndex()
	{
		$limit = 10;
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=0");
		$criteria->addCondition("type=1");
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
		$this->_data['waterfall_api_url'] = '/Api/Pin.List?type=1';

		$this->pageTitle = "旅游特价情报站首页";
		$this->render('index',$this->_data);
	}
	
	public function actionTejia()
	{
		$limit = 10;
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=0");
		$criteria->addCondition("type=1");
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
		$this->_data['waterfall_api_url'] = '/Api/Pin.List?type=1';
		
		$this->pageTitle = "特价信息 - 特价机票，特价酒店";
		$this->render('tejia',$this->_data);
	}
	public function actionGonglue()
	{
		$limit = 10;
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=0");
		$criteria->addCondition("type=2");
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
		$this->_data['waterfall_api_url'] = '/Api/Pin.List?type=2';
		$this->pageTitle = "微攻略";
		$this->render('gonglue',$this->_data);
	}

	public function actionTop()
	{
		$limit = 10;
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=0");
		$criteria->addCondition("type=1");
		$criteria->order = ' `view_count` DESC';
		$criteria->limit = $limit;
		$count = Pin::model()->count($criteria);
		$data = Pin::model()->findAll($criteria);
		$pin_list = array();
		foreach($data as $pin)
		{
			$pin_list[] = $this->_format_pin($pin);
		}
		$this->_data['pin_list'] = $pin_list;
		$this->_data['waterfall_api_url'] = '/Api/Pin.List?type=1&sort=view_count';
		$this->pageTitle = "每周推荐";
		$this->render('tejia',$this->_data);
	}

	public function actionListAjax()
	{
		$p = intval($_GET['p']) > 1 ? intval($_GET['p']) : 1;
		$per_page = 10;
		$offset = ($p - 1) * $per_page;
		$limit = $per_page; 
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=0");
		if(isset($_GET['type'])) {
			$type = intval($_GET['type']);
			$criteria->addCondition("type=$type");
		}
		$criteria->order = ' `ctime` DESC';
		if(isset($_GET['sort']) && in_array($_GET['sort'],array('view_count'))) { 
			$criteria->order = ' view_count DESC';
		}
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
		$this->pageTitle = "添加信息";
		$this->render('/pin/edit_pin',$this->_data);
	}

	public function actionEdit($id)
	{
		$pin_db = Pin::model()->findByPk($id);
		if(empty($pin_db))
			throw new CHttpException(404,'Not found');
		$this->_check_pin_author($pin_db);
		$this->_data['pin_db'] = $pin_db;
		$this->pageTitle = "编辑";
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
		$data['title'] = htmlspecialchars(addslashes(trim($_POST['title'])));
		$data['content'] = htmlspecialchars(addslashes($_POST['content'])); 
		$data['content'] = htmlspecialchars(addslashes(($_POST['content']))); 
		$data['desc'] = htmlspecialchars(addslashes($_POST['desc']));
		$data['tags'] = htmlspecialchars(addslashes($_POST['tags']));
		$data['cover_image'] = $_POST['cover_image_id'];
		$data['cover_image_width'] = $_POST['cover_image_width'];
		$data['cover_image_height'] = $_POST['cover_image_height'];
		$data['cron_pub'] = isset($_POST['cron_pub']) ? $_POST['cron_pub'] : '';
		$data['cron_time'] = isset($_POST['cron_time']) ? trim($_POST['cron_time']) : '';
		$data['is_sync_weibo'] = $_POST['is_sync_weibo'] ? 1 : 0;
		$data['type'] = isset($_POST['type']) ? $_POST['type'] : 1;

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
		$is_cron_pub = (isset($data['cron_pub']) && $data['cron_pub'] && !empty($data['cron_time'])) ? true : false;
		$new_pin = new Pin;
		$new_pin->type = $data['type'];
		$new_pin->title = $data['title'];
		$new_pin->content = $data['content'];
		$new_pin->desc = $data['desc'];
		$new_pin->tags = $data['tags'];
		$new_pin->cover_image = $data['cover_image'];
		$new_pin->cover_image_width = $data['cover_image_width'];
		$new_pin->cover_image_height = $data['cover_image_height'];
		$new_pin->user_id = Yii::app()->user->user_id;
		$new_pin->is_sync_weibo = $data['is_sync_weibo'];
		$new_pin->ctime = time();
		$new_pin->status = $is_cron_pub ? 1 : 0;

		if($new_pin->save())
		{
			$new_pin_id = $new_pin->pin_id;
			// 更新用户发表数量
			$this->_update_user_stats();
			$this->_data['pin_id'] = $new_pin_id;

			if($is_cron_pub) {
				$data['pin_id'] = $new_pin_id;
				if(! $this->_create_cron_job($data))
					$this->ajax_response(false,'定时任务发布失败');
			} else if($data['is_sync_weibo'] == 1) {
				Yii::import('ext.sinaWeibo.SinaWeibo',true);
				$text = cut_str($data['desc'],120);
				$c = new SaeTClientV2( WB_AKEY , WB_SKEY , Yii::app()->user->out_token);
				if(!empty($data['cover_image']))
				{
					$r = $c->upload($text, upimage($data['cover_image'],'big'));
				} else {
					$r = $c->update($text);
				}
			}
			$this->ajax_response(true,'',$this->_data);
		} else {
			$this->ajax_response(false,'插入失败');
		}
	}

	private function _create_cron_job($data)
	{
		$new_job = new MetaJob;
		$new_job->meta_action = 'process_cron_pub_pin';  //定时任务函数名，会于cli 里对应
		$new_job->params = json_encode(array('pin_id'=>$data['pin_id']));
		$new_job->status = 0;
		$new_job->ctime = time();
		$new_job->cron_time = $data['cron_time'];

		if($new_job->save())
			return true;
		else
			return false;

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

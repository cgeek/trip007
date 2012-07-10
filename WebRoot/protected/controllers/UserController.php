<?php
Yii::import('ext.sinaWeibo.SinaWeibo',true);

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	private $_identity = null;
	private $_data = null;
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('signup','register','login','logout','followers','following','timeline','detail'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('home','apps','settings','update','timelineAjax','myweibo','followAjax','unfollowAjax'),
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
	
	public function actionDetail($id)
	{
		$user_db = User::model()->findByPk($id);
		if(empty($user_db) || $user_db->status < 0)
			throw new CHttpException(404,'Not found');

		$this->_data['user'] = $user_db;
		if(!Yii::app()->user->isGuest && Yii::app()->user->user_id === $id)
		{
			$this->_data['myself'] = true;
		} else {
			$this->_data['myself'] = false;
		}
		$limit = 10;
		$criteria = new CDbCriteria;
		$criteria->addCondition("user_id=$id");
		$criteria->addCondition("status=0");
		$criteria->order = ' `ctime` DESC';
		$criteria->limit = $limit;
		$count = Pin::model()->count($criteria);
		$data = Pin::model()->findAll($criteria);
		$pin_list = array();
		foreach($data as $pin)
		{
			$pin_list[] = format_pin($pin);
		}
		$this->_data['pin_list'] = $pin_list;
		//$this->ajax_response(true,'',$this->_data);

		if(!Yii::app()->user->isGuest) {
			$followed = UserRelation::model()->find('user_id=:user_id AND follow_id=:follow_id AND type=1',array('user_id'=>Yii::app()->user->user_id, 'follow_id'=>$id));
		}
		if(isset($followed) && !empty($followed)) {
			$this->_data['followed'] = true;
		} else {
			$this->_data['followed'] = false;
		}
		$this->render('/user/detail',$this->_data);
	}

	public function actionHome()
	{
		$id = Yii::app()->user->user_id;
		$user_db = User::model()->findByPk($id);
		if(empty($user_db) || $user_db->status < 0)
			throw new CHttpException(404,'Not found');

		$this->_data['user'] = $user_db;
		$limit = 10;
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=0");
		$criteria->addCondition("user_id=$id");
		$criteria->order = ' `ctime` DESC';
		$criteria->limit = $limit;
		$count = Pin::model()->count($criteria);
		$data = Pin::model()->findAll($criteria);
		$pin_list = array();
		foreach($data as $pin)
		{
			$pin_list[] = format_pin($pin);
		}
		$this->_data['pin_list'] = $pin_list;
		//$this->ajax_response(true,'',$this->_data);
		
		$this->render('/user/home',$this->_data);
	}

	public function actionTimeline($id)
	{
		$user_db = User::model()->findByPk($id);
		if(empty($user_db) || empty($user_db['out_token']))
		{
			echo 'user not existed';
			die();
		}
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $user_db['out_token']);
		$home_timeline = $c->user_timeline_by_id($user_db['out_uid'], 1, 50, 0, 0, 1);
		$data = array();
		foreach($home_timeline['statuses'] as $feed) {
			$feed['text'] = $this->_process_content($feed['text']);
		    $feed['created_at'] = date('Y-m-d H:i:s',strtotime($feed['created_at']));
		    $data[] = $feed;
		}
		$this->_data['pin_list'] = $data;
		$this->render('timeline',$this->_data);
	}
	
	public function actionTimelineAjax()
	{
		$user_id = $_GET['user_id'];
		$user_db = User::model()->findByPk($user_id);
		if(empty($user_db) || empty($user_db['out_token']))
		{
			$this->ajax_response(false,'用户不存在');	
		}
		$p = $_GET['p'];
		$p = intval($p) <= 0 ? intval($p) : 1;
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , Yii::app()->user->out_token);
		$home_timeline = $c->user_timeline_by_id($user_db['out_uid'], $p, 50, 0, 0, 1);
		$data = array();
		foreach($home_timeline['statuses'] as $feed) {
			$feed['text'] = $this->_process_content($feed['text']);
		    $feed['created_at'] = date('Y-m-d H:i:s',strtotime($feed['created_at']));
			$data[] = $feed;
		}
		$this->ajax_response(true,'',$data);
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

	public function actionMyweibo()
	{
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , Yii::app()->user->out_token);
		$home_timeline = $c->user_timeline_by_id($user_db['out_uid'], 1, 50, 0, 0, 1);
		$data = array();
		foreach($home_timeline['statuses'] as $feed) {
			$feed['text'] = $this->_process_content($feed['text']);
		    $feed['created_at'] = date('Y-m-d H:i:s',strtotime($feed['created_at']));
		    $data[] = $feed;
		}
		$this->_data['pin_list'] = $data;
		$this->render('home',$this->_data);
	}

	public function actionFollowAjax()
	{

		$follow_id = $_POST['user_id'];
		$user_db = User::model()->findByPk($follow_id);
		if(empty($user_db) || $user_db->status < 0)
			$this->ajax_response(false,'用户不存在');

		$user_id = Yii::app()->user->user_id;
		$followed = UserRelation::model()->find('user_id=:user_id AND follow_id=:follow_id AND type=1',array('user_id'=>$user_id, 'follow_id'=>$follow_id));
		if($followed) {
			$this->ajax_response(false,'已经关注过');
		}
		$follow_model = new UserRelation;
		$follow_model->user_id = $user_id;
		$follow_model->follow_id = $follow_id;
		$follow_model->type = 1;
		$follow_model->save();
		
		$fansed = UserRelation::model()->find('user_id=:user_id AND follow_id=:follow_id AND type=0',array('user_id'=>$follow_id, 'follow_id'=>$user_id));
		if($fansed) {
			$this->ajax_response(false,'已经关注过');
		}
		$fans_model = new UserRelation;
		$fans_model->user_id = $follow_id;
		$fans_model->follow_id = $user_id;
		$fans_model->type = 0;
		$fans_model->save();

		$this->_update_stats($user_id);
		$this->_update_stats($follow_id);

		$this->ajax_response(true,'');
	}

	public function actionUnFollowAjax()
	{
		$follow_id = $_POST['user_id'];
		$user_id = Yii::app()->user->user_id;
		UserRelation::model()->deleteAll('user_id=:user_id AND follow_id=:follow_id AND type=1',array('user_id'=>$user_id, 'follow_id'=>$follow_id));
		UserRelation::model()->deleteAll('user_id=:follow_id AND follow_id=:user_id AND type=0',array('user_id'=>$user_id, 'follow_id'=>$follow_id));

		$this->_update_stats($user_id);
		$this->_update_stats($follow_id);

		$this->ajax_response(true,'');
	}

	public function actionFollowing()
	{

	}

	public function actionFollowers()
	{

	}
	private function _update_stats($user_id)
	{
		$fans_count = UserRelation::model()->count('user_id=:user_id AND type=1',array('user_id'=>$user_id));
		$follow_count = UserRelation::model()->count('user_id=:user_id AND type=0',array('user_id'=>$user_id));
		$update_data = array('fans_count'=>$fans_count, 'follow_count'=>$follow_count);
		User::model()->updateByPK($user_id,$update_data);
	}

	public function actionSetting()
	{
		$this->render('setting');
	}

	public function actionLogin()
	{
		if(Yii::app()->request->isAjaxRequest) {
			$email = $_POST['email'];
			$password = $_POST['password'];
			if(empty($email) || empty($password)) {
				$this->ajax_response(false, "邮箱或者密码不能为空");
			}
			if($this->_identity===null)
			{
				$this->_identity=new UserIdentity($email,$password);
				$this->_identity->authenticate();
			}
			if($this->_identity->errorCode === UserIdentity::ERROR_NONE)
			{
				$rememberMe = $_POST['remember'];
				$duration=($rememberMe === 1) ? 3600*24*30 : 0; // 30 days

				Yii::app()->user->login($this->_identity,$duration);
				$this->ajax_response(true, "恭喜你，登录成功！");
			} else {
				if($this->_identity->errorCode === UserIdentity::ERROR_PASSWORD_INVALID) {
					$this->ajax_response(false, "密码不正确，请重新输入");
				} else if($this->_identity->errorCode === UserIdentity::ERROR_USERNAME_INVALID) { 
					$this->ajax_response(false, "你输入的邮箱不正确，请重新输入");
				}
			}
		} else {
			if(Yii::app()->user->isGuest) {
				$this->render('login');
			} else {
				$this->redirect(array('user/home'));
			}
		}
	}

	public function actionSettings()
	{	
		$this->render('settings');
	}

	public function actionSignup()
	{
		$this->renderPartial('signup');
	}

	public function actionRegister()
	{
		if(Yii::app()->request->isAjaxRequest) {
			$email = $_POST['email'];
			$password = $_POST['password'];
			if(empty($email) || empty($password)) {
				$this->ajax_response(false, "邮箱或者密码不能为空");
			}
			$user = User::model()->find("email=:email",array(":email"=>$email));
			if(!empty($user)) {
				$this->ajax_response(false, "该邮箱已经使用，请换其他邮箱");
			}
			$user_model=new User;
			$user_model->email = $email;
			$user_model->password =  md5($password);
			$user_model->user_name  = substr($email,0,strpos($email,"@"));
			$user_model->ctime = time();
			$success = false;
			$message = "";
			if($user_model->save()) {
				$this->_identity=new UserIdentity($email,$password);
				$this->_identity->authenticate();
				Yii::app()->user->login($this->_identity,3600*24*30);
				$this->ajax_response(true, "恭喜你注册成功!");
			} else {
				$this->ajax_response(false, "注册失败，请重新注册");
			}
		} else {
			$this->render('register');
		}
	}
	
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$referrer = Yii::app()->request->getUrlReferrer();
		$referrer = !empty($referrer) ? $referrer : '/';
		$this->redirect($referrer);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

}

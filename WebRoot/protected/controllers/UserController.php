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
				'actions'=>array('register','login','logout','timeline','detail'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('home','apps','setting','update','timelineJson'),
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
	
	public function actionTimelineJson()
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

	public function actionHome()
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
	public function actionApps()
	{
		$this->render('apps');
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->user_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->user_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
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

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

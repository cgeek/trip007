<?php
Yii::import('ext.sinaWeibo.SinaWeibo',true);

class OauthController extends Controller
{
	private $_identity = NULL;

	public function actionWeibo()
	{
		$weiboService=new SinaWeibo(WB_AKEY, WB_SKEY);
		$code_url = $weiboService->getAuthorizeURL( WB_CALLBACK_URL );
		Yii::app()->session['back_url'] = Yii::app()->request->getUrlReferrer();
		$this->redirect($code_url);
	}

	public function actionWeiboCallback(){
		$weiboService=new SinaWeibo(WB_AKEY, WB_SKEY);
		if (isset($_REQUEST['code'])) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = WB_CALLBACK_URL;
			try {
				$token = @$weiboService->getAccessToken( 'code', $keys ) ;
			} catch (OAuthException $e) {
			}
		}

		if ($token) {
			$_SESSION['token'] = $token;
			setcookie( 'weibojs_'.$weiboService->client_id, http_build_query($token));
			$this->process_out_callback();
		} else {
			echo '认证失败';
		}
	}

	private function process_out_callback()
	{
		$access_token =  $_SESSION['token']['access_token'];
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $access_token);
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		if(empty($uid))
		{
			echo "登录失败，未知错误: ";
			Yii::log('oauth error:'. var_dump($uid_get));
			return ;
		}
		$user_info = $c->show_user_by_id($uid);//根据ID获取用户等基本信息
		if(empty($user_info))
		{
			echo "登录失败，不能取得用户信息 ";
			return ;
		}
		//echo json_encode($user_info);die();
		$user_db = User::model()->find("out_uid=:out_uid",array(":out_uid"=>$uid));
		if(!empty($user_db)) {
			$this->_identity=new UserIdentity($user_info['id'],'','weibo');
			$this->_identity->authenticate();
			Yii::app()->user->login($this->_identity,3600*24*30);
			User::model()->updateByPk($user_db['user_id'], array('out_token'=>$access_token,'last_login_time'=>time()));
			$this->redirect(Yii::app()->session['back_url']);
		} else {
			$new_user = new User;
			$new_user->user_name = $user_info['screen_name'];
			$new_user->province = $user_info['province'];
			$new_user->location = $user_info['location'];
			$new_user->avatar = $user_info['profile_image_url'];
			$new_user->avatar_large = $user_info['avatar_large'];
			$new_user->gender = $user_info['gender'];
			$new_user->description = mysql_escape_string($user_info['description']);
			$new_user->out_source = 'weibo';
			$new_user->out_uid = $user_info['id'];
			$new_user->out_token = $_SESSION['token']['access_token'];
			$new_user->ctime = time();
			$new_user->status = 0;
			if($new_user->save()) {
				//process login 
				$this->_identity=new UserIdentity($user_info['id'],'','weibo');
				$this->_identity->authenticate();
				Yii::app()->user->login($this->_identity,3600*24*30);
				$this->redirect(Yii::app()->session['back_url']);
			} else {
				header( "refresh:3;url=http://www.trip007.net/");
				echo "<h1>添加用户失败！将会在3秒之后跳转到首页,请重新登录。如果没有，点击<a href=\"/\">这里</a>。</h1>";
				die();
			}
		}
	}

}

<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	public $user;
	
	public $out_login;

	private $out_array = array('weibo','qq','douban');

	public function __construct($username,$password, $out_login = NULL)
	{
		$this->username = $username;
		$this->password = $password;
		$this->out_login = $out_login;
	}
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		if($this->out_login && in_array($this->out_login, $this->out_array))
		{
			$this->authenticate_oauth();
		} 
		else 
		{
			$this->authenticate_local();
		}
		return $this->errorCode;
	}

	private function authenticate_local()
	{
		$user = User::model()->findByAttributes(array('email'=>CHtml::encode($this->username)));
		if($user == null) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} else {
			if ($user->password !== md5($this->password)) {
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
			} else {
				$this->_id = $user->user_id;
				$this->errorCode = self::ERROR_NONE;
				if(isset($user['password'])) { 
					unset($user['password']); 
				}
				$this->setUser($user);
				if (null === $user->last_login_time) {
					$lastLogin = time();
				} else {
					$lastLogin = strtotime($user->last_login_time);
				}
			}
		}
		unset($user);
	}

	private function authenticate_oauth()
	{
		$user = User::model()->findByAttributes(array('out_uid'=>$this->username,'out_source'=>$this->out_login));
		if($user == null) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} else {
			$this->_id = $user->user_id;
			$this->errorCode = self::ERROR_NONE;
			if(isset($user['password'])) { 
				unset($user['password']); 
			}
			$this->setUser($user);
			if (null === $user->last_login_time) {
				$lastLogin = time();
			} else {
				$lastLogin = strtotime($user->last_login_time);
			}
		}
		unset($user);
	}

	public function getId()
	{
		return $this->_id;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setUser(CActiveRecord $user)
	{
		$this->user = $user->attributes;
	}
}

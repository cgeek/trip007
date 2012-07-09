<?php

/**
 *  通过扩展CWebUser添加信息到Yii:app()->user
 *  1、添加$user属性到UserIdentity类。
 *  添加getUser()方法-getter上面这个属性
 *  加setUser($user)方法-setter上面这个属性，它可以赋值给user的信息通过$user这个属性。
 *  
 *  2. 修改 config/main.php 里的User
 *
 */
class WebUser extends CWebUser 
{

	public function __get($name)
	{
		if ($this->hasState('__userInfo')) {
			$user=$this->getState('__userInfo',array());
			if (isset($user[$name])) {
				return $user[$name];
			}
		}

		return parent::__get($name);
	}

	public function login($identity, $duration) {
		$this->setState('__userInfo', $identity->getUser());
		parent::login($identity, $duration);
	}

	public function afterLogin()
	{
		Yii::log('login success');
	}
}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="zh" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/global.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/base.css" />
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/libs/seajs/1.1.0/sea.js"></script>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
<div class="header">
	<div id="header-top">
		<div class="main">
			<div class="logo">
				<a title="旅游特价情报站" href="/">旅游特价情报站</a>
			</div>
			<div class="tips">
				<span>发现最超值的旅游特价信息</span>
			</div>
			<div class="weibo-follow">
				<iframe width="136" scrolling="no" height="24" frameborder="0" src="http://widget.weibo.com/relationship/followbutton.php?language=zh_cn&amp;width=136&amp;height=24&amp;uid=1776438131&amp;style=2&amp;btn=light&amp;dpc=1" border="0" marginheight="0" marginwidth="0" allowtransparency="true"></iframe>
			</div>
		</div>
	</div>

	<div class="header-nav">
		<div class="main clearfix">
			<ul class="channel clearfix">
				<li class="first"><a href="/" class="on">首页</a></li>
				<li>
					<dl class="clearfix">
						<dt><a href="/tejia">特价信息</a></dt>
						<dd><a href="/search/tejia/酒店">酒店</a></dd>
						<dd><a href="/search/tejia/机票">机票</a></dd>
					</dl>
				</li>
				<li>
					<a href="/top">每周推荐</a>
				</li>
				<li>
					<a href="/gonglue">微攻略</a>
				</li>
				<!--li>
					<a href="/zhuangbei/">装备</a>
				</li-->

			</ul>
			</ul>
<?php if(! Yii::app()->user->isGuest): ?>
			<div class="user_nav">
				<a class="dropdown-toggle" href="/user/home" data-toggle="dropdown"><img src="<?=Yii::app()->user->avatar;?>" width=25><?php echo Yii::app()->user->user_name;?>
					<b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
					<li><a href="#">Action</a></li>
					<li><a href="#">Another action</a></li>
					<li><a href="#">Something else here</a></li>
					<li class="divider"></li>
					<li><a href="#">Separated link</a></li>
				</ul>
			</div>
<?php else:?>
			<div class="header_login">
				<a href="/oauth/weibo" class="login-button weibo">
					<img src="/images/loginbtn_weibo.png">
				</a>
			</div>
<?php endif;?>
		</div>
	</div>
</div>
<?php if(false && Yii::app()->user->isGuest):?>
<div class="main">
	<div id="unauth_callout">
		<div class="sheet">
			<!--div class="unauth-connect">
				<h5>使用合作网站帐号登录</h5>
				<a href="/oauth/weibo" class="login-button weibo">使用微博账号登陆</a>
			</div-->
			<div class="unauth-btns">
				<a href="/oauth/weibo" class="login-button weibo">
					<img src="/images/loginbtn_weibo.png">
				</a>
				<!--a href="/user/signup" class="btn btn18 rbtn">
					<strong> 注册 »</strong><span></span>
				</a>
				<a href="/user/login/" class="btn btn18 wbtn">
					<strong>登录</strong><span></span>
				</a-->
			</div>
			<div class="tips">
				<span>发现最超值的旅游特价信息</span>
				<div class="weibo-follow">
					<iframe width="136" scrolling="no" height="24" frameborder="0" src="http://widget.weibo.com/relationship/followbutton.php?language=zh_cn&amp;width=136&amp;height=24&amp;uid=1776438131&amp;style=2&amp;btn=light&amp;dpc=1" border="0" marginheight="0" marginwidth="0" allowtransparency="true"></iframe>
				</div>
			</div>
		</div>
	</div>
</div>
<?php else:?>

	<!--div id="unauth_callout">
		<div class="sheet">
			<div class="unauth-connect">
				<h5>你有情报要发布？</h5>
				<a href="/pin/add" >提供特价情报</a>
			</div>
			<div class="unauth-btns">
				<a href="/login/" class="btn btn18 wbtn">
					<strong>搜索</strong><span></span>
				</a>
				<a href="/user/signup" class="btn btn18 rbtn">
					<strong> 订阅 »</strong><span></span>
				</a>
			</div>
			<div class="tips">
				<span>发现最超值的旅游特价信息</span>
			</div>
			<div class="search-box">
				<input type="text" name="place" value="" placeholder="搜索特价情报">
			</div>
		</div>
	</div-->
<?php endif;?>
<?php echo $content; ?>

<div id="footer">
	Copyright &copy; 2011 - <?php echo date('Y'); ?> 旅游特价情报站 	All Rights Reserved.  友情连接:<a href="http://www.qiugonglue.com" target="_blank">求攻略</a><br/>&nbsp;&nbsp; 业务合作：<a href="http://weibo.com/happyweekend" target="_blank">weibo.com/happyweekend</a>
</div><!-- footer -->

</body>
</html>

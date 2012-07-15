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
						<dd><a href="/search/tejia">其他</a></dd>
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
				<a href="/user/home" ><img src="<?=Yii::app()->user->avatar;?>" width=25><?php echo Yii::app()->user->user_name;?>
					<b class="caret"></b>
				</a>
				<ul class="user-nav-menu">
					<li><a href="/user/home">我的关注</a></li>
					<li><a href="/user/sub">我的订阅</a></li>
					<li><a href="/user/<?=Yii::app()->user->user_id;?>">我的发布</a></li>
					<li><a href="/pin/add">发布特价信息</a></li>
					<li><a href="/pin/add?type=2">发布微攻略</a></li>
					<li><a href="/user/settings">个人设置</a></li>
					<li><a href="/logout">退出登录</a></li>
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
<?php echo $content; ?>
<a href="#" id="return_top" class="back_to_top" style="">回到顶部</a>
<div id="footer">
	Copyright &copy; 2011 - <?php echo date('Y'); ?> 旅游特价情报站 	All Rights Reserved.  友情连接:<a href="http://www.qiugonglue.com" target="_blank">求攻略</a><br/>&nbsp;&nbsp; 业务合作：<a href="http://weibo.com/happyweekend" target="_blank">weibo.com/happyweekend</a>
</div><!-- footer -->

</body>
</html>

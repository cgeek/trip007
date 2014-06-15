<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="zh" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/global.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/base.css" />
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/libs/seajs/1.1.0/sea.js"></script>
	<title><?php echo CHtml::encode($this->pageTitle); ?> -- 提供最新旅游特价信息</title>
	<meta name="keywords" content="旅游特价信息网,旅游特价信息,旅游特价信息情报站，特价信息，旅游，旅游特价，特价机票，特价酒店，最后一分钟，自助游, 泰国，亚航，曼谷，普吉岛，马尔代夫"/>
	<meta name="author" content="旅游特价信息网,旅游特价信息情报站"/>
	<meta name="Description" content="旅游特价信息网,每天给你提供最新的机票，酒店，旅游线路等方面的特价信息."/>
</head>
<body>
<div class="header">
	<div id="header-top">
		<div class="main">
			<div class="logo">
				<a title="出国去哪儿" href="/">出国去哪儿!</a>
			</div>
			<div class="tips">
				<span>发现最超值的旅游特价信息</span>
			</div>
			<div class="weibo-follow">
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
						<dd><a href="/tejia/酒店">酒店</a></dd>
						<dd><a href="/tejia/机票">机票</a></dd>
						<dd><a href="/tejia">其他</a></dd>
					</dl>
				</li>
				<li>
					<a href="/top">每周推荐</a>
				</li>
				<li>
					<a href="/gonglue">微攻略</a>
				</li>
				<li>
					<a href="http://hotels.trip007.cn/" target="_blank">酒店比价</a>
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
					<li><a href="/note/add">发表游记</a></li>
					<li><a href="/sale/add">发布特价信息</a></li>
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
	Copyright &copy; 2011 - <?php echo date('Y'); ?> 旅游特价情报站 	All Rights Reserved.  友情连接:<a href="http://www.qiugonglue.com" target="_blank">求攻略</a><br/>&nbsp;&nbsp;
</div><!-- footer -->
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Ff0c718a170d8d6c5a80ba21c05a35d65' type='text/javascript'%3E%3C/script%3E"));
</script>

</body>
</html>

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
<div id="header">
	<div class="main">
		<div class="logo">
			<a title="旅游特价情报站" href="/">旅游特价情报站</a>
		</div>
<?php if(! Yii::app()->user->isGuest): ?>
		<div class="user_nav">
			<a href="/user/home"><img src="<?=Yii::app()->user->avatar;?>" width=25><?php echo Yii::app()->user->user_name;?></a>
			 | &nbsp;&nbsp; <a href="/user/logout">退出登录</a>
		</div>
<?php endif;?>
	</div>

</div>

<?php echo $content; ?>

<div id="footer">
	Copyright &copy; 2011 - <?php echo date('Y'); ?> 旅游特价情报站 	All Rights Reserved.  友情连接:<a href="http://www.qiugonglue.com" target="_blank">求攻略</a><br/>&nbsp;&nbsp; 业务合作：<a href="http://weibo.com/happyweekend" target="_blank">weibo.com/happyweekend</a>
</div><!-- footer -->

</body>
</html>

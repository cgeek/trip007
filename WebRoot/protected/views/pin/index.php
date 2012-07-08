<div class="main">
<?php if(Yii::app()->user->isGuest):?>
	<div id="unauth_callout">
		<div class="sheet">
			<div class="unauth-connect">
				<h5>使用合作网站帐号登录</h5>
				<a href="/oauth/weibo" class="login-button weibo">使用微博账号登陆</a>
			</div>
			<div class="unauth-btns">
				<a href="/login/" class="btn btn18 wbtn">
					<strong>搜索</strong><span></span>
				</a>
				<a href="/user/signup" class="btn btn18 rbtn">
					<strong> 注册 »</strong><span></span>
				</a>
			</div>
			<div class="tips">
				<span>发现最超值的旅游特价信息</span>
				<div class="weibo-follow">
					<iframe width="136" scrolling="no" height="24" frameborder="0" src="http://widget.weibo.com/relationship/followbutton.php?language=zh_cn&amp;width=136&amp;height=24&amp;uid=1776438131&amp;style=2&amp;btn=light&amp;dpc=1" border="0" marginheight="0" marginwidth="0" allowtransparency="true"></iframe>
				</div>
			</div>
			<div class="search-box">
				<input type="text" name="place" value="" placeholder="搜索特价情报">
			</div>
		</div>
	</div>
<?php else:?>
	<div id="unauth_callout">
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
	</div>
<?php endif;?>
<div id="waterfall" class="clearfix" style="visibility:hidden;" data-url="/Api/Pin.Hot?">
	<?php if(!empty($pin_list)):?>
		<?php foreach($pin_list as $pin):?>
	<div class="pin item">
		<div class="cover_image"><a href="/pin/<?=$pin['pin_id'];?>"><img width=222 height="<?=$pin['cover_image_height'];?>" src="<?=$pin['cover_image_mw'];?>" ></a></div>
		<p class="title"><a href="/pin/<?=$pin['pin_id'];?>"><?=$pin['title'];?></a></p>
		<p class="content">	<?=$pin['desc'];?> </p>
		<div class="author">
			<a class="author_avatar" href="/user/<?=$pin['user']['user_id'];?>">
				<img src="<?=$pin['user']['avatar'];?>" width=30 > 
			</a>
			<div class="username">
				<a href="/user/<?=$pin['user']['user_id'];?>" target="_blank"><?=$pin['user']['user_name'];?> </a>
			<br>
			<span><?=$pin['ctime'];?></span>
			</div>
		</div>
	</div>
		<?php endforeach;?>
	<?php endif;?>
</div>
<div class="loading" id="more-loading">
	<img src="/images/loading.gif">
	<span>正在加载</span>
</div>
</div>
<script type="js/template" id="item_list_tpl">
{{#pin_list}}
	<div class="pin item">
		<div class="cover_image"><a href="/pin/{{pin_id}}"><img width=222 height="{{cover_image_height}}" src="{{cover_image_mw}}" ></a></div>
		<p class="title"><a href="/pin/{{pin_id}}">{{title}}</a></p>
		<p class="content">{{{text}}}</p>
		<div class="author">
			<a class="author_avatar" href="/user/{{user.user_id}}">
				<img src="{{user.avatar}}" width=30 > 
			</a>
			<div class="username">
				<a href="http://weibo.com/{{user.domain}}" target="_blank">{{user.screen_name}} </a>
			<br>
			<span>{{ctime}}</span>
			</div>
		</div>
	</div>
{{/pin_list}}
</script>
<script>
seajs.use('/assets/js/router.js',function(router){
	router.load('pin_list');
});
</script>

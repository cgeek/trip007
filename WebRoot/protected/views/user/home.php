<div class="main">
	<div id="nav_bar">
		<div class="cases">
			<a id="nav_bar_latest" href="/user/home" class="active"><em></em>我的关注</a>
			<a id="nav_bar_top20" href="/user/sub" class=""><em></em>我的订阅</a>
		</div>
		<div class="places">
		</div>
		<div class="add_pin">
		<a href="/user/<?=$user['user_id'];?>">我的发布</a>
		</div>
	</div>
<div id="waterfall" class="clearfix" style="visibility:hidden;" data-url="<?=$waterfall_api_url;?>">
	<div class="pin item" id="user_info">
		<div class="profile">
			<div class="profile-basic">
				<a class="img" href="/user/<?=$user['user_id'];?>"><img src="<?=$user['avatar_large'];?>" width=64 height=64></a>
				<a href="/user/<?=$user['user_id'];?>" class="user_name"><?=$user['user_name'];?></a>
				<a href="/user/settings" class="settings">账号设置</a>
			</div>
			<div class="profile-stats">
			<a href="/user/<?=$user['user_id'];?>"><strong><?=$user['pin_count'];?></strong>信息</a>
				<a href="/user/following/<?=$user['user_id'];?>"><strong><?=$user['follow_count'];?></strong>关注</a>
				<a href="/user/followers/<?=$user['user_id'];?>" class="last"><strong><?=$user['fans_count'];?></strong>粉丝</a>
			</div>
			<div class="profile=acts">
			</div>
		</div>
	</div>
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
		<p class="content">{{{desc}}}</p>
		<div class="author">
			<a class="author_avatar" href="/user/{{user.user_id}}">
				<img src="{{user.avatar}}" width=30 > 
			</a>
			<div class="username">
				<a href="/user/{{user.user_id}}" target="_blank">{{user.user_name}} </a>
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

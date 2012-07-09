<div class="main">
<div id="waterfall" class="clearfix" style="visibility:hidden;" data-url="/Api/Pin.list?user_id=<?=$user['user_id'];?>">
	<div class="pin item" id="user_info">
		<div class="profile-sidebar">
			<h1><?=$user['user_name'];?></h1>
			<p class="stats">
				<a href="#">关注<span><?=$user['follow_count'];?></span></a>
				<a href="#">粉丝<span><?=$user['fans_count'];?></span></a>
			</p>
			<p class="profile-avatar">
				<a class="img" href="/user/<?=$user['user_id'];?>"><img src="<?=$user['avatar_large'];?>" width=222></a>
<?php if($myself):?>
				<a href="/user/settings" class="btn wbtn">
					<strong>账号设置</strong><span></span>
				</a>
<?php else:?>
			<?php if(!$followed):?>
				<a href="#" class="btn rbtn follow_btn" user_id="<?=$user['user_id'];?>">
					<strong>关注</strong><span></span>
				</a>
			<?php else:?>
				<a href="#" class="btn wbtn unfollow_btn" user_id="<?=$user['user_id'];?>">
					<strong>取消关注</strong><span></span>
				</a>
			<?php endif;?>
<?php endif;?>
			</p>
			<p class="profile-desc">
				<?=$user['description'];?>
			</p>
			<p class="less"><?=$user['location'];?></p>
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

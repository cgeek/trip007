<div class="main">
<?php if(Yii::app()->user->isGuest):?>
	<div id="unauth_callout">
		<div class="sheet">
			<div class="unauth-connect">
				<h5>使用合作网站帐号登录</h5>
				<a href="/oauth/weibo">微薄登录</a>
			</div>
		</div>
	</div>
<?php endif;?>
<div id="waterfall" class="clearfix" style="visibility:hidden;" data-url="/Api/User.TimelineJson?user_id=19">
	<?php if(!empty($pin_list)):?>
		<?php foreach($pin_list as $pin):?>
	<div class="pin item">
		<div class="cover_image"><img width=222 src="<?=$pin['bmiddle_pic'];?>" ></div>
		<p class="content">	<?=$pin['text'];?> </p>
		<div class="author">
			<div class="author_avatar">
				<img src="<?=$pin['user']['profile_image_url'];?>" width=30 > 
			</div>
			<div class="">
			来自微博<a href="http://weibo.com/<?=$pin['user']['domain'];?>" target="_blank"><?=$pin['user']['screen_name'];?> </a>
			<br>
			<span><?=$pin['created_at'];?></span>
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
{{#data}}
	<div class="pin item">
		<div class="cover_image"><img width=222 src="{{bmiddle_pic}}" ></div>
		<p class="content">{{{text}}}</p>
		<div class="author">
			<div class="author_avatar">
				<img src="{{user.profile_image_url}}" width=30 > 
			</div>
			<div class="">
			来自微博<a href="http://weibo.com/{{user.domain}}" target="_blank">{{user.screen_name}} </a>
			<br>
			<span><{{created_at}}</span>
			</div>
		</div>
	</div>
{{/data}}
</script>
<script>
seajs.use('/assets/js/router.js',function(router){
	router.load('pin_list');
});
</script>

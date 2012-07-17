<div class="main">
	<div id="nav_bar">
		<div class="cases">
			<a id="nav_bar_latest" href="/gonglue" class="active"><em></em>最新微攻略</a>
			<a id="nav_bar_top20" href="/gonglue?filter=top" class=""><em></em>精品推荐</a>
		</div>
		<div id="nav_bar_more_places">
			<div class="row">
				<ul>
					<li><a href="香港" >香港</a></li>
					<li><a href="香港" >香港</a></li>
					<li><a href="香港" >香港</a></li>
				</ul>
			</div>
		</div>
		<div class="places">
			<a href="/search/gonglue/行程">行程</a>
			<a href="/search/gonglue/机票">机票</a>
			<a href="/search/gonglue/出入境">出入境</a>
			<a href="/search/gonglue/购物">购物</a>
			<a href="/search/gonglue/美食">美食</a>
		</div>
<?php if(!Yii::app()->user->isGuest):?>
		<div class="add_pin">
			<a href="/pin/add?type=2"> + 发布攻略</a>
		</div>
<?php endif;?>
	</div>
<div id="waterfall" class="clearfix" style="visibility:hidden;" data-url="/Api/PinList?type=2&sort=view_count">
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
<div class="modal hide fade" id="pin_detail_modal"></div>
<script type=“js/template" id="pin_detail_tpl">
<div class="pin_content"></div>
</script>
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

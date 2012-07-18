<div id="waterfall" class="clearfix" style="visibility:hidden;" data-url="<?=$waterfall_api_url;?>">
	<?php if(!empty($pin_list)):?>
		<?php foreach($pin_list as $pin):?>
	<div class="pin item">
		<div class="cover_image"><a target="_blank" href="/pin/<?=$pin['pin_id'];?>"><img width=222 height="<?=$pin['cover_image_height'];?>" src="<?=$pin['cover_image_mw'];?>" ></a></div>
		<p class="title"><a target="_blank" href="/pin/<?=$pin['pin_id'];?>"><?=$pin['title'];?></a></p>
		<p class="content">	<?=$pin['desc'];?> </p>
		<div class="author">
			<a target="_blank" class="author_avatar" href="/user/<?=$pin['user']['user_id'];?>">
				<img src="<?=$pin['user']['avatar'];?>" width=30 > 
			</a>
			<div class="username">
				<a target="_blank" href="/user/<?=$pin['user']['user_id'];?>" target="_blank"><?=$pin['user']['user_name'];?> </a>
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

<script type="js/template" id="item_list_tpl">
{{#pin_list}}
<div class="pin item">
	<div class="cover_image"><a target="_blank" href="/pin/{{pin_id}}"><img width=222 height="{{cover_image_height}}" src="{{cover_image_mw}}" ></a></div>
	<p class="title"><a target="_blank" href="/pin/{{pin_id}}">{{title}}</a></p>
	<p class="content">{{{desc}}}</p>
	<div class="author">
	<a target="_blank" class="author_avatar" href="/user/{{user.user_id}}">
	<img src="{{user.avatar}}" width=30 > 
	</a>
	<div class="username">
	<a target="_blank" href="/user/{{user.user_id}}" target="_blank">{{user.user_name}} </a>
	<br>
	<span>{{ctime}}</span>
	</div>
	</div>
	</div>
{{/pin_list}}
</script>

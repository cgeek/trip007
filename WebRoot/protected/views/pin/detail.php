<div class="main clearfix">
	<div class="pin-detail-wrapper clearfix">
		<div class="pin-pinner">
			<a href="/user/<?=$pin['user_id'];?>" class="pinner-avatar"><img src="<?=$pinner['avatar'];?>"></a>
			<p class="pinner-name"><a href="/user/<?=$pin['user_id'];?>"><?=$pinner['user_name'];?></a></p>
			<p class="pinner-stats less">发布于<?=human_time($pin['ctime']);?></p>
		</div>
		<div class="pin-content">
			<?php if($is_author):?>
				<div class="pin-opt">
					<a href="/pin/edit/<?=$pin['pin_id'];?>">编辑</a> | <a href="javascript:void(0);" class="delete_pin" pin_id="<?=$pin['pin_id'];?>">删除</a>
				</div>
			<?php endif;?>
			<?php if(!empty($pin['title'])):?><h2><?=$pin['title'];?></h2><?php endif;?>
			<?=htmlspecialchars_decode($pin['content']);?>
			<img src="<?=upimage($pin['cover_image'],'big');?>">
		</div>	

	</div>
</div>

<script>
seajs.use('/assets/js/router.js',function(router){
	router.load('head.js');
});
</script>

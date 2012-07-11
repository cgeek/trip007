<div class="main">
	<div class="pb-wrapper clearfix">
	<form action="/pin/save_pin" method="POST">
		<input type="hidden" name="pin_id" value="<?=isset($pin_db['pin_id']) ? $pin_db['pin_id'] :'';?>">
		<input type="hidden" name="cover_image_id" value="<?=isset($pin_db['cover_image']) ? $pin_db['cover_image'] :'';?>">
		<input type="hidden" name="cover_image_width" value="<?=isset($pin_db['cover_image_width']) ? $pin_db['cover_image_width'] :'';?>">
		<input type="hidden" name="cover_image_height" value="<?=isset($pin_db['cover_image_height']) ? $pin_db['cover_image_height'] :'';?>">
		<div class="pb-main">
			<h2 class="pb-main-title"><span>发布旅游特价信息</span></h2>
			<div class="pb-post-area">
				<div class="pb-image-holder">
					<div id="file-uploader"><noscript><p>请打开浏览器的javascript功能</p></noscript></div>
					<div class="cover_image_show">
<?php if(isset($pin_db['cover_image'])):?>
	<img src="<?=upimage($pin_db['cover_image'],'small');?>">
<?php endif;?>
					</div>
				</div>
				<div class="pb-title-holder">
					<h3 class="pb-section-title">特价情报标题<span>(选填:30个字以内)</span></h3>
					<input type="text" class="pb-title-input" name="title" value="<?=isset($pin_db['title']) ? $pin_db['title'] :'';?>">
				</div>
				<div class="pb-desc-holder">
					<h3 class="pb-section-title">特价情报概述<span>(必填:140个字以内)</span></h3>
					<textarea name="desc" class="pb-desc-textarea"><?=isset($pin_db['desc']) ? $pin_db['desc'] :'';?></textarea>
				</div>
				<div class="pb-content-holder">
					<h3 class="pb-section-title">详细内容<span>(必填)</span></h3>
					<div class="pb-content-editor">
						<script type="text/plain" id="editor" name="pin-content" style="float:left;width:660px;">
						<?=isset($pin_db['content']) ? htmlspecialchars_decode($pin_db['content']) :'';?>
						</script>
					</div>
				</div>
			</div>
			<div class="pb-action-holder">
				<button class="btn pb-submit-btn">发布</button>
			</div>
		</div>
		<div class="pb-aside">
			<div class="pb-aside-inbox">
				<div id="post-privacy-holder">
					<div class="post-privacy-select">
						<ul class="private-menu">
						
						</ul>
					</div>
				</div>
				<div id="post-tags-holder">
					<textarea name="tags" place="添加关联目的地,用逗号或者回车分割"></textarea>
				</div>
			</div>
		</div>

	</form>
	</div>
</div>

<script type="text/javascript">
<!--
window.UEDITOR_HOME_URL = '/assets/js/libs/ueditor/1.2.2/';
//-->
</script>
<script>
seajs.use('/assets/js/router.js',function(router){
	router.load('add_pin');
});
</script>

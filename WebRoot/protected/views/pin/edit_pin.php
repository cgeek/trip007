<div class="main">
	<div class="pb-wrapper clearfix">
	<form action="/pin/save_pin" method="POST">
		<input type="hidden" name="pin_id" value="<?=isset($pin_db['pin_id']) ? $pin_db['pin_id'] :'';?>">
		<input type="hidden" name="cover_image_id" value="<?=isset($pin_db['cover_image']) ? $pin_db['cover_image'] :'';?>">
		<input type="hidden" name="cover_image_width" value="<?=isset($pin_db['cover_image_width']) ? $pin_db['cover_image_width'] :'';?>">
		<input type="hidden" name="cover_image_height" value="<?=isset($pin_db['cover_image_height']) ? $pin_db['cover_image_height'] :'';?>">

		<?php $is_edit = isset($pin_db) ? true : false ;?>
		<?php $is_gonglue = ((isset($_GET['type']) && $_GET['type'] ==2) || (isset($pin_db) && $pin_db['type'] == 2) ) ? true : false ;?>
		<input type="hidden" name="type" value="<?=$is_edit ? $pin_db['type'] : $is_gonglue ? 2:1;?>">
		<div class="pb-main">
			<h2 class="pb-main-title">
				<span>
				<?php echo $is_edit ? '编辑':'发布';?><?php echo $is_gonglue ? '旅游攻略':'特价信息' ;?>
				</span>
			</h2>
			<div class="pb-post-area">
				<div class="pb-image-holder">
					<div id="file-uploader"><noscript><p>请打开浏览器的javascript功能</p></noscript></div>
					<div class="cover_image_show">
<?php if(isset($pin_db['cover_image'])):?>
	<img src="<?=upimage($pin_db['cover_image'],'small');?>">
<?php endif;?>
					</div>
					<div class="cover_image_show_layout">
<?php if(isset($pin_db['cover_image'])):?>
	<img src="<?=upimage($pin_db['cover_image'],'big');?>">
<?php endif;?>
					</div>
				</div>
				<div class="pb-title-holder">
					<h3 class="pb-section-title">标题<span>(选填:30个字以内)</span></h3>
					<input type="text" class="pb-title-input" name="title" value="<?=isset($pin_db['title']) ? $pin_db['title'] :'';?>">
				</div>
				<div class="pb-desc-holder">
					<h3 class="pb-section-title">概述<span>(必填:120个字以内。如果同步到微博，同步此内容)</span></h3>
					<textarea name="desc" class="pb-desc-textarea"><?=isset($pin_db['desc']) ? $pin_db['desc'] :'';?></textarea>
					<span class="counter"></span>
				</div>
				<div class="pb-content-holder">
					<h3 class="pb-section-title">详细内容<span>(必填)</span></h3>
					<div class="pb-content-editor">
						<script type="text/plain" id="editor" name="pin-content" style="float:left;width:660px;">
						<?=isset($pin_db['content']) ? stripslashes(htmlspecialchars_decode($pin_db['content'])) :'';?>
						</script>
					</div>
				</div>
			</div>
			<div class="pb-action-holder">
			<?php if(isset($pin_db)):?>
				<button class="btn pb-submit-btn">保存修改</button>
			<?php else: ?>
				<button class="btn pb-submit-btn">立即发布</button>
			<?php endif;?>
			</div>
		</div>
		<div class="pb-aside">
			<div class="pb-aside-inbox">
				<div id="post-privacy-holder">
					<div class="post-privacy-select">
				<?php if(isset($pin_db) && $pin_db['status'] == 0):?>
						<div class="pb-type-selected">
							<span class="selected">已发布</span>
						</div>
				<?php else:?>
						<div class="pb-type-select">
							<span class="selected">立即发布</span>
							<span class="combobox-arrow"></span>
						</div>
						<ul class="private-menu">
							<li type="now">立即发布</li>
							<li type="cron">定时发布</li>
						</ul>
						<input type="hidden" name="cron_time"  value="<?=date('Y-m-d H:i',time());?>">
						<input type="hidden" name="cron_pub" value="0">
				<?php endif;?>
					</div>
				</div>
				<div class="cron-time-holder">
					<input type="text" name="date" id="datepicker" value="<?=date('Y-m-d',time());?>" style="width:80px;">
					<input type="text" name="hour" id="time_hour" value="<?=date('H',time());?>" style="width:25px;text-align:center;"> :
					<input type="text" name="minute" id="time_minute" value="<?=date('i',time());?>" style="width:25px;text-align:center;">
				</div>
				<div id="post-tags-holder">
					<textarea name="tags" placeholder="添加关联目的地,用逗号隔开；如香港,泰国"><?=isset($pin_db['tags']) ? stripslashes(htmlspecialchars_decode($pin_db['tags'])) :'';?></textarea>
				</div>
				<div class="bind_weibo_sync">
				<?php if(isset($pin_db) && $pin_db['status'] == 0):?>
					<?php if($pin_db['is_sync_weibo'] == 1):?> 已经同步到微博<?php endif;?>
				<?php else:?>
					<input type="checkbox" name="is_sync_weibo" value="1" checked><span>同步到新浪微博</span>
				<?php endif;?>
					<!--p><a href="">绑定账号，同步到微博 ->></a></p-->
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

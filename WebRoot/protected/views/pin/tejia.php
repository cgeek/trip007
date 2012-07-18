<div class="main">
	<div id="nav_bar">
		<div class="cases">
			<a id="nav_bar_latest" href="/tejia" class="active"><em></em>最新特价</a>
			<a id="nav_bar_top20" href="/top" class=""><em></em>每周推荐</a>
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
			<a href="/tejia/马尔代夫">马尔代夫</a>
			<a href="/tejia/沙巴">沙巴</a>
			<a href="/tejia/曼谷">曼谷</a>
			<a href="/tejia/普吉岛">普吉岛</a>
			<a href="/tejia/吉隆坡">吉隆坡</a>
		</div>
<?php if(!Yii::app()->user->isGuest):?>
		<div class="add_pin">
			<a href="/pin/add"> + 发布特价信息</a>
		</div>
<?php endif;?>
	</div>

<?php $this->renderPartial('/layouts/waterfall',array('pin_list'=>$pin_list,'waterfall_api_url'=>$waterfall_api_url)); ?>

<div class="modal hide fade" id="pin_detail_modal"></div>
<script type=“js/template" id="pin_detail_tpl">
<div class="pin_content"></div>
</script>

<script>
seajs.use('/assets/js/router.js',function(router){
	router.load('pin_list');
});
</script>

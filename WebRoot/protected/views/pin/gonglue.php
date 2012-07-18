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
			<a href="/gonglue/行程">行程</a>
			<a href="/gonglue/机票">机票</a>
			<a href="/gonglue/出入境">出入境</a>
			<a href="/gonglue/购物">购物</a>
			<a href="/gonglue/美食">美食</a>
		</div>
<?php if(!Yii::app()->user->isGuest):?>
		<div class="add_pin">
			<a href="/pin/add?type=2"> + 发布攻略</a>
		</div>
<?php endif;?>
	</div>
	<?php $this->renderPartial('/layouts/waterfall',array('pin_list'=>$pin_list,'waterfall_api_url'=>$waterfall_api_url)); ?>
</div>
<div class="modal hide fade" id="pin_detail_modal"></div>
<script type=“js/template" id="pin_detail_tpl">
<div class="pin_content"></div>
</script>
<script>
seajs.use('/assets/js/router.js',function(router){
	router.load('pin_list');
});
</script>

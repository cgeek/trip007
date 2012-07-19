<div class="main">
	<div class="welcome-banner" style="padding:10px;height:250px;border-radius:5px;background:#fff;">
		<div id="pinCarousel" class="carousel slide" style="width:555px;float:left;height:250px;">
			<div class="carousel-inner">
				<div class="item active">
					<a target="_blank" href="/pin/19"> <img src="http://qimages.b0.upaiyun.com/08fbdbbc0ffd77e2dfcb52242551ea5a_big.jpg" height="250" width="555"> </a>
					<div class="carousel-caption">
						<h4><a target="_blank" href="/pin/19" style="color:#fff;">泰航促销，北京往返曼谷税前机票1910元起，特价机票需在官网上用银联卡支付</a></h4>
					</div>
				</div>
				<div class="item">
					<a target="_blank" href="/pin/17"> <img src="http://qimages.b0.upaiyun.com/bcdd3786761b75cecb5dcf25d0e2ccb4_big.jpg" height="250" width="555"> </a>
					<div class="carousel-caption">
						<h4><a target="_blank" href="/pin/17" style="color:#fff;">捷星航空新加坡～大阪特价，特惠票价新加坡元168起</a></h4>
					</div>
				</div>
				<div class="item">
					<a target="_blank" href="/pin/13"> <img src="http://qimages.b0.upaiyun.com/6e1a962bfdaece8336435010b527eb74_big.jpg" height="250" width=555></a>
					<div class="carousel-caption">
						<h4><a target="_blank" href="/pin/13" style="color:#fff;">亚洲航空机票预订超级详细图解</a></h4>
					</div>
				</div>
			</div>
			<!-- Carousel nav -->
			<a class="carousel-control left" href="#pinCarousel" data-slide="prev">&lsaquo;</a>
			<a class="carousel-control right" href="#pinCarousel" data-slide="next">&rsaquo;</a>
		</div>
		<div style="float:right;">
		<iframe frameborder="0" style="display:block;" width="350px" height="250px" src="http://www.hotelscombined.com/Affiliate/Widgets/HC_large/default.aspx?a_aid=68060&languageCode=CS&openInNewWindow=1" scrolling="no" allowtransparency="true" ></iframe>
		<!--
		<script type="text/javascript" src="http://api.skyscanner.net/api.ashx?key=3e0a870b-406d-4798-bba8-73dd095a081"></script>  
<script type="text/javascript">  
skyscanner.load('snippets','2',{cultureid:'zh'});  
function main(){  
	var snippet=new skyscanner.snippets.SearchPanelControl();  
	snippet.setCurrency('CNY');  
	snippet.setShape('box300x250');  
	snippet.setDeparture('cn');  
	snippet.draw(document.getElementById('snippet_searchpanel'));  
}  
skyscanner.setOnLoadCallback(main);  
</script>    

			<div id="snippet_searchpanel" style="width: auto; height:auto;">  
				<a href="http://www.tianxun.cn/flights-from/cn/cheap-flights-from-china.html" target="_blank">由 中国出发的廉价航班</a>  
			</div>  
		-->
		</div>
	</div>
</div>

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
			<a target="_blank" href="/pin/add"> + 发布特价信息</a>
		</div>
<?php endif;?>
	</div>
</div>
<div class="main">
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

<div class="main">
	<div class="welcome-banner" style="padding:10px;height:250px;border-radius:5px;background:#fff;">
		<div style="width:555px;float:left;">
			<img src="http://qimages.b0.upaiyun.com/3b7bec258201143d80414e64ccd7e505_big.jpg" height="250" width=555>
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
			<a href="/pin/add"> + 发布特价信息</a>
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

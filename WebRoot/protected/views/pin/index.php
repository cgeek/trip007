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
	<div id="nav_bar">
		<div class="cases">
			<a id="nav_bar_latest" href="/" class="active"><em></em>最新特价</a>
			<a id="nav_bar_top20" href="/pin/top" class=""><em></em>每日推荐</a>
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
			<a href="/pin/search/">马尔代夫</a>
			<a href="/pin/search/">沙巴</a>
			<a href="/pin/search/">泰国</a>
			<a href="/pin/search/">普吉岛</a>
			<a href="/pin/search/">国内</a>
		</div>
<?php if(!Yii::app()->user->isGuest):?>
		<div class="add_pin">
			<a href="/pin/add"> + 发布情报</a>
		</div>
<?php endif;?>
	</div>
<div id="waterfall" class="clearfix" style="visibility:hidden;" data-url="/Api/Pin.Hot?">
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

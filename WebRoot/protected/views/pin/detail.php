<div class="main clearfix">
	<div class="pin-detail-wrapper clearfix">
		<div class="pin-pinner">
			<a href="/user/<?=$pin['user_id'];?>" class="pinner-avatar"><img src="<?=$pinner['avatar'];?>"></a>
			<p class="pinner-name"><a href="/user/<?=$pin['user_id'];?>"><?=$pinner['user_name'];?></a></p>
			<p class="pinner-stats less">发布于<?=human_time($pin['ctime']);?></p>
		</div>
		<div class="pin-content">
			<?php if(isset($is_author) && $is_author):?>
				<div class="pin-opt">
					<a href="/pin/edit/<?=$pin['pin_id'];?>">编辑</a> | <a href="javascript:void(0);" class="delete_pin" pin_id="<?=$pin['pin_id'];?>">删除</a>
				</div>
			<?php endif;?>
			<?php if(!empty($pin['title'])):?><h2><?=$pin['title'];?></h2><?php endif;?>
			<?=stripslashes(htmlspecialchars_decode($pin['content']));?>
		</div>	
		
	</div>
	<div class="aside">
		<a href="http://www.hotelscombined.com/?a_aid=68060&label=详情页右侧边栏&languageCode=CS" target="_blank" rel="nofollow"><img src="http://media.datahc.com/banners/affiliate/cs/simple_300x250.png"  border="0" /></a>
		<div style="margin-top:20px;">
			<script type='text/javascript' src='http://www.hotelscombined.com/SearchBox/130461'></script>
		</div>
		<div style="margin-top:20px;">
			<script type="text/javascript" src="//api.skyscanner.net/api.ashx?key=db739f90-ca38-4db7-ae7c-03a8caca2661"></script>
<script type="text/javascript">
   skyscanner.load("snippets","2");
      function main(){
		         var snippet = new skyscanner.snippets.SearchPanelControl();
				        snippet.setMarket("CN")
							       snippet.setCulture("zh-CN");
				        snippet.setCurrency("CNY");
				        snippet.setShape("box300x250");


       snippet.draw(document.getElementById("snippet_searchpanel"));
	      }
		     skyscanner.setOnLoadCallback(main);
			 </script>
			 <div id="snippet_searchpanel" style="width: auto; height:auto;"></div>
		</div>
	</div>
</div>

<script>
seajs.use('/assets/js/router.js',function(router){
	router.load('head.js');
});
</script>

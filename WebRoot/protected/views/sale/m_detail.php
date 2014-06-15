<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=yes" name="format-detection" />
    <title>
	<?php if(!empty($sale['title'])):?><?=$sale['title'];?><?php endif;?>
    </title>

<link href="http://static.qyer.com/min/?version=1402652503&&f=m/basic/css/base.css,m/basic/css/headfoot.css,m/project/lm/css/lm.css" rel="stylesheet" type="text/css" media="screen" />
    <style>
        img {width:98%;}
    </style>
</head>
<body>
    <?php if(!empty($sale['title'])):?>
        <h2 class="lmDetailTitle"><?=$sale['title'];?></h2>
    <?php endif;?>

    <section class="lmDetailZK clearfix">
        <div class="lmDetailZKnr clearfix">
            <div style="background-image:" class="leftimg fl">
                <img src="<?=$sale['cover_image'];?>" alt="全国多城市出发巴黎+阿姆斯特丹7天5晚自由行特价">
               <!-- 倒计时 -->
           </div>
           <div class="rightnr">
                <p class="price"><em><?=$sale['price'];?></em>元</p>
            </div>
        </div>
        <h4 class="explan"><em><?=$sale['view_count'];?></em>次浏览</h4>
    </section>

    <section class="lmbox" style="margin-top:10px;">
        <h2 class="title"><em class="lmxq"></em><i>折扣详情</i></h2>
        <article class="cnt">
	        <?=stripslashes(htmlspecialchars_decode(nl2br($sale['content'])));?>
        </article>
    </section>

</body>
</html>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=yes" name="format-detection" />
    <title>
	<?php if(!empty($note['title'])):?><?=$note['title'];?><?php endif;?>
    </title>
    <style>
        img {width:98%;}
    </style>
</head>
<body>
	<?php if(!empty($note['title'])):?><h2><?=$note['title'];?></h2><?php endif;?>
	<?=stripslashes(htmlspecialchars_decode($note['content']));?>
</body>
</html>

<?php

defined('DS') or define('DS',DIRECTORY_SEPARATOR);
function app() 
{
	return Yii::app();
}

function user() 
{
	return Yii::app()->user;
}

function h($text)
{
	return htmlspecialchars($text,ENT_QUOTES,Yii::app()->charset);
}

function l($text, $url = '#', $htmlOptions = array()) 
{
	return CHtml::link($text, $url, $htmlOptions);
}

// get upyun image url
function upimage($image_id, $size = 'medium')
{
	if(empty($image_id) || $image_id == md5(''))
	{
		return FALSE;
	}
	if(strlen($image_id) != 32) {
		return $image_id;
	}
	return "http://qimages.b0.upaiyun.com/{$image_id}_{$size}.jpg";
}

//format time to human
function human_time($small_ts, $large_ts=false) {
	if(!$large_ts) $large_ts = time();
	$n = $large_ts - $small_ts;
	if($n <= 1) return '1 秒前';
	if($n < (60)) return $n . ' 秒前';
	if($n < (60*60)) { $minutes = round($n/60); return '' . $minutes . '分钟' .'前'; }
	if($n < (60*60*16)) { $hours = round($n/(60*60)); return '' . $hours . '小时' . '前'; }
		if($n < (time() - strtotime('yesterday'))) return '昨天';
	if($n < (60*60*24)) { $hours = round($n/(60*60)); return '' . $hours . '小时' . '前'; }
		if($n < (60*60*24*6.5)) return '' . round($n/(60*60*24)) . '天前';
	if($n < (time() - strtotime('last week'))) return '上周';
	if(round($n/(60*60*24*7))  == 1) return '一周前';
	if($n < (60*60*24*7*3.5)) return '' . round($n/(60*60*24*7)) . '周前';
	if($n < (time() - strtotime('last month'))) return '上个月';
	if(round($n/(60*60*24*7*4))  == 1) return '一个月前';
	if($n < (60*60*24*7*4*11.5)) return '' . round($n/(60*60*24*7*4)) . '月前';
	if($n < (time() - strtotime('last year'))) return '去年';
	if(round($n/(60*60*24*7*52)) == 1) return '一年以前';
	if($n >= (60*60*24*7*4*12)) return '' . round($n/(60*60*24*7*52)) . '年前'; 
	return false;
}

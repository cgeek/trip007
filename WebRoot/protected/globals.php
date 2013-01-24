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
		return '';
	}
	if(strlen($image_id) != 32) {
		return $image_id;
	}
	return "http://qimages.b0.upaiyun.com/{$image_id}_{$size}.jpg";
}

function fixed_pin_height($width, $height, $waterfall_pic_width = 222)
{
	$fixed = array('width'=>0,'height'=>0);
	if($width > $waterfall_pic_width && $height >0)
	{
		$fixed['width'] = $waterfall_pic_width;
		$fixed['height'] = (int) ($waterfall_pic_width * $height / $width );
	} elseif($width > 0 && $height > 0 ) {
		$fixed['width'] = $width;
		$fixed['height'] = $height;
	}
	return $fixed;
}


/*
Utf-8、gb2312都支持的汉字截取函数
cut_str(字符串, 截取长度, 开始长度, 编码);
编码默认为 utf-8
开始长度默认为 0
*/
function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
{
	if($code == 'UTF-8')
	{
		$pa ="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
		preg_match_all($pa, $string, $t_string);        if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
		return join('', array_slice($t_string[0], $start, $sublen));
	}
	else
	{
		$start = $start*2;
		$sublen = $sublen*2;
		$strlen = strlen($string);
		$tmpstr = '';        for($i=0; $i<$strlen; $i++)
	{
		if($i>=$start && $i<($start+$sublen))
		{
			if(ord(substr($string, $i, 1))>129)
			{
				$tmpstr.= substr($string, $i, 2);
			}
			else
			{
				$tmpstr.= substr($string, $i, 1);
			}
		}
		if(ord(substr($string, $i, 1))>129) $i++;
	}
		if(strlen($tmpstr)<$strlen ) $tmpstr.= "...";
		return $tmpstr;
	}
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

function format_pin($pin)
{
	$format_pin = array();
	$user = User::model()->findByPk($pin->user_id);

	$format_pin['pin_id'] = $pin->pin_id;
	$format_pin['title'] = $pin->title;
	$format_pin['desc'] = empty($pin->desc) ? mb_substr(strip_tags(htmlspecialchars_decode($pin->content)),0,200,'utf-8') : htmlspecialchars_decode($pin->desc);
	$format_pin['cover_image_b'] = upimage($pin->cover_image,'big');
	$format_pin['cover_image_m'] = upimage($pin->cover_image,'medium');
	$format_pin['cover_image_mw'] = upimage($pin->cover_image,'mw');
	$fixed_width = fixed_pin_height($pin->cover_image_width, $pin->cover_image_height);
	$format_pin['cover_image_width'] = $fixed_width['width'];
	$format_pin['cover_image_height'] = $fixed_width['height'];
	$format_pin['user'] = array('user_id'=> $user->user_id,'user_name'=>$user->user_name,'avatar'=>$user->avatar);
	$format_pin['ctime'] = date("Y-m-d H:i",$pin->ctime);
	return $format_pin;
}

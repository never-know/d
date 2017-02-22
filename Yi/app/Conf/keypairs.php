<?php

function article_tags($key = null)
{
	static $arr = [0=> '全部', 1 => '品牌文化传播', 2 => '吃喝玩乐', 3 => '生活服务', 127 => '其他'];
	return $key ? $arr[$key] : $arr;
}
 
function article_order($key = null)
{
	static $arr = [1 => 'id', 2 => 'ctime', 3 => 'end'];
	return $key ? $arr[$key] : $arr;
}
  
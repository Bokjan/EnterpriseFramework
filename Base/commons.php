<?php
/**
 * EnterPrise Framework
 * 框架函数库（单字母方法）
 * @version 0.1.0
 * @author Bokjan Chan
 **/

/**
 *C方法返回系统配置值
 *@param $key 配置数组键名
 */
function C($key){
	$ep_config=require(EP_PATH.'Conf/conf.php');
	if(isset($ep_config[$key])){
		return $ep_config[$key];
	}
	else{
		return NULL;
	}
}
/**
 *U方法返回一个URL地址
 *@param $string URL信息
 */
function U($string){
	$sepe=C('URL_SEPE');
	if($sepe==NULL){
		$sepe='/';
	}
	$string=explode('?', $string);
	$class=explode('/', $string[0]);
	$param=explode('&', $string[1]);
	$url=$class[0].$sepe.$class[1].$sepe;
	$i=1;
	$j=count($param);
	foreach ($param as $element) {
		$element=explode('=', $element);
		$url.=$element[0].$sepe.$element[1];
		if($i<$j){
			$url.=$sepe;
		}
		$i++;
	}
	if(C('URL_MODE')==NULL||C('URL_MODE')==0){
		$url='./index.php/'.$url;
	}
	else{
		$url='./'.$url;
	}
	$url.=C('URL_SUFFIX');
	return $url;
}
?>
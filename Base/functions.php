<?php
/**
 * EnterPrise Framework
 * 基本函数库
 * @version 0.1.5
 * @author Bokjan Chan
 **/
/**
 *返回目录下文件名的索引数组
 *@param string $path 相对目录
 */
function get_folder($path){
	$scanner=opendir(EP_PATH.$path);
	$list=array();
	while(($file=readdir($scanner))!==false){
		if ($file!='.'&&$file!='..') {
			$list[]=$file;
		}
	}
	closedir($scanner);
	return $list;
}
/**
 *包含一个第三方类
 *@param string $name 类名(*.class.php)
 *@param boolean $type=false 是否标准命名
 *$type为假时，传入*.class.php的*即可
 *为真时，传入完整名称如epdb.php
 */
function vendor($name,$type=false){
	if ($type) {
		require(EP_PATH.'Vendor/'.$name.'.class.php');
	} else {
		require(EP_PATH.'Vendor/'.$name);
	}
	return;
}
/**
 *简化的cookie操作
 *@param string $name 名
 *@param string $value=NULL 值
 *@param int $expire=NULL 过期时间
 */
function cookie($name,$value=NULL,$expire=NULL){
	if ($value==NULL) {
		if (isset($_COOKIE[$name])) {
			return $_COOKIE[$name];
		} else {
			return NULL;
		}
		
	} else {
		$time=time();
		if ($expire==NULL){
			setcookie($name,$value,$time+3600);
		} else {
			setcookie($name,$value,$time+$expire);
		}
		return;
	}
}
/**
 *简化的cookie操作
 *@param string $name 名
 *@param string $value=NULL 值
 */
function session($name,$value=NULL){
	session_start();
	if ($value==NULL) {
		if (isset($_SESSION[$name])) {
			return $_SESSION[$name];
		} else {
			return NULL;
		}
	} else {
		$_SESSION[$name]=$value;
		return;
	}
}
/**
 *内存开销统计
 *@param string $type=NULL 返回类型种类(千字节/兆字节)(非必须)
 */
function memory_stat($type=NULL){
	$memory=memory_get_usage();
	if($type=='KB'){
		return round(($memory/1024),6);
	}
	elseif($type=='MB'){
		return round(($memory/1048576),6);
	}
	else{
		return $memory;
	}
}
?>
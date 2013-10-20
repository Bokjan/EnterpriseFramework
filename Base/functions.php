<?php
/**
 * EnterPrise Framework
 * 基本函数库
 * @version 0.1.7
 * @author Bokjan Chan
 **/
/**
 *调用类时自动包含对应文件
 *@return void
 */
function cloader($class=NULL){
	static $list;
	if ($class!=NULL) {
		$class=strtolower($class);
		$mark=false;
		foreach($list as $file){
			if(strstr(strtolower($file), $class)){
				require_once(EP_PATH.$file);
				$mark=true;
			}
		}
		if(!$mark){
			$ep_prog=new EpException();
			$ep_prog->output("无法加载类{$class}");
		}
	} else {
		foreach(get_folder('Model') as $name){
			$list[]='Model/'.$name;
		}
		foreach(get_folder('Action') as $name){
			$list[]='Action/'.$name;
		}
	}
}
/**
 *返回目录下文件名的索引数组
 *@param string $path 相对目录
 *@return array 文件列表
 */
function get_folder($path){
	$scanner=opendir(EP_PATH.$path);
	$list=array();
	while(($file=readdir($scanner))!==false){
		if ($file!='.'&&$file!='..'&&strstr($file, '.php')) {
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
 *@return void
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
 *包含一个框架非必须类或方法
 *@param string $name 类名(*.class.php)
 *@return void
 */
function import($name){
		require(EP_PATH.'Lib/Util/'.$name.'.class.php');
	return;
}
/**
 *简化的cookie操作
 *@param string $name 名
 *@param string $value=NULL 值
 *@param int $expire=NULL 过期时间
 *@return string | void
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
 *@return string | void
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
 *内存峰值开销统计
 *@param string $type=NULL 返回类型种类(千字节/兆字节)(非必须)
 *@return float 峰值内存(指定单位)
 */
function memory_stat($type=NULL){
	$memory=memory_get_peak_usage();
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
/**
 *运行时间计算
 *@return int 运行耗时（毫秒）
 */
function time_stat(){
	$now=explode(' ', microtime());
	$now=$now[0]+$now[1];
	$total=($now-EP_START)*1000;
	return round($total,4);
}
?>
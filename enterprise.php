<?php
/**
 * EnterPrise Framework
 * @version 0.1.5
 * @author Bokjan Chan
 **/
defined('EP_PATH') or define('EP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');
//计时开始
$ep_start=explode(' ',microtime());
define('EP_START',($ep_start[0]+$ep_start[1]));
unset($ep_start);
//读取基本方法
require(EP_PATH.'Base/functions.php');
require(EP_PATH.'Base/commons.php');
//注册自动包含类函数
spl_autoload_register('cloader');
//初始化文件列表
cloader();
//初始化配置
$_config=require(EP_PATH.'Conf/conf.php');
C($_config);
//初始化钩子
$_hook=require(EP_PATH.'Conf/hook.php');
H($_hook);
//初始化语言
$_lang=cookie('lang');
if($_lang==NULL){
	$_lang=C('LANG');
	define('LANG',C('LANG'));
	cookie('lang',C('LANG'),time()+2592000);
}
elseif($_lang=='zh_cn'){
	define('LANG',$_lang);
}
//睿云引擎相关
if (SMARTCLOUD) {
	//检测是否为新浪应用引擎
	if(function_exists('saeAutoLoader')){
		define('IS_CLOUD', true);
		define('IS_SAE',true);
		define('IS_BAE',false);
		defined('CLOUD_TYPE') or define('CLOUD_TYPE', 'SAE');
	}
	//检测是否为百度应用引擎
	if(isset($_SERVER['HTTP_BAE_ENV_APPID'])){
		define('IS_CLOUD', true);
		define('IS_BAE',true);
		define('IS_SAE',false);
		defined('CLOUD_TYPE') or define('CLOUD_TYPE', 'BAE');
	}
}
else{
	define('IS_CLOUD', false);
	define('IS_SAE',false);
	define('IS_BAE',false);
	define('CLOUD_TYPE',NULL);
}
if (IS_CLOUD) {
	require(EP_PATH.'Base/io_'.strtolower(CLOUD_TYPE).'.php');
} else {
	require(EP_PATH.'Base/io.php');
}
//读取其他框架文件
require(EP_PATH.'Lib/Action.class.php');
require(EP_PATH.'Lib/Enterprise.class.php');
require(EP_PATH.'Lib/epdb.class.php');
unset($_config,$_wrapper,$_lang);
//运行框架
Enterprise::run();
?>
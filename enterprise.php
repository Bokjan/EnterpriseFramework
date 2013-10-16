<?php
/**
 * EnterPrise Framework
 * @version 0.1.4
 * @author Bokjan Chan
 **/
defined('EP_PATH') or define('EP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');
//读取基本方法
require(EP_PATH.'Base/functions.php');
require(EP_PATH.'Base/commons.php');
//自动包含类相关操作
spl_autoload_register('cloader');
cloader();
//读取配置
$_config=require(EP_PATH.'Conf/conf.php');
C($_config);
//初始化钩子
$_hook=require(EP_PATH.'Conf/hook.php');
H($_hook);
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
foreach (get_folder('Lib') as $name) {
	require_once(EP_PATH.'Lib/'.$name);
}
unset($_config,$_wrapper,$name);
//运行框架
Enterprise::run();
?>
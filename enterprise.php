<?php
/**
 * EnterPrise Framework
 * @version 0.1.2
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
unset($_config);
//读取其他框架文件
foreach (get_folder('Lib') as $name) {
	require(EP_PATH.'Lib/'.$name);
}
unset($name);
//运行框架
Enterprise::run();
?>
<?php
/**
 * EnterPrise Framework
 * @version 0.1.1
 * @author Bokjan Chan
 **/
defined('EP_PATH') 	or define('EP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');
//读取基本方法
require(EP_PATH.'Base/functions.php');
require(EP_PATH.'Base/commons.php');
//读取其他框架文件
foreach (get_folder('Lib') as $name) {
	require(EP_PATH.'Lib/'.$name);
}
foreach (get_folder('Function') as $name) {
	require(EP_PATH.'Function/'.$name);
}
foreach (get_folder('Action') as $name) {
	require(EP_PATH.'Action/'.$name);
}
unset($name);
//运行框架
Enterprise::run();
?>
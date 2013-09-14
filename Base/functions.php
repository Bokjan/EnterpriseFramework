<?php
/**
 * EnterPrise Framework
 * 基本函数库
 * @version 0.1.0
 * @author Bokjan Chan
 **/
/**
 *get_folder方法返回目录下文件名的索引数组
 *@param $path 相对目录
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
?>
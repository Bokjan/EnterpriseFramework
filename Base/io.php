<?php
/**
 * EnterPrise Framework
 * IO函数及初始化操作_标准环境
 * @version 0.1.0
 * @author Bokjan Chan
 **/
/**
 *文件缓存方法
 *@param string $key 缓存文件键
 *@param mixed $value 缓存数据
 *@param integer $expire 过期时间(秒)
 *@return NULL | mixed
 */
function F($key,$value=NULL,$expire=NULL){
	if ($value==NULL) {
		$value=unserialize(file_get_contents(EP_PATH.'Base/Cache/'.$key.'.cache'));
		if(!isset($value['expire'])){
			return $value['value'];
		}
		else{
			if ($value['expire']<=time()) {
				return $value['value'];
			} else {
				return NULL;
			}
		}
	} else {
		if ($expire!=NULL) {
			$expire+=time();
			$value=array(
				'value'=>$value,
				'expire'=>$expire
				);
		}
		else{
			$value=array('value'=>$value);
		}
		$value=serialize($value);
		file_put_contents(EP_PATH.'Base/Cache/'.$key.'.cache', $value);
		return;
	}
}
?>
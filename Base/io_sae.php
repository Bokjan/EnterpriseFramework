<?php
/**
 * EnterPrise Framework
 * IO函数及初始化操作_新浪引擎
 * @version 0.1.0
 * @author Bokjan Chan
 **/
//SAE REQUIRE
require_once(EP_PATH.'Lib/Enterprise.class.php');
require_once(EP_PATH.'Lib/epdb.class.php');
require_once(EP_PATH.'Lib/Action.class.php');
//设置数据库
$data=array(
	'DB_USER'=>SAE_MYSQL_USER,
	'DB_PW'=>SAE_MYSQL_PASS,
	'DB_HOST'=>SAE_MYSQL_HOST_M,
	'DB_PORT'=>SAE_MYSQL_PORT,
	'DB_NAME'=>SAE_MYSQL_DB
);
C($data);
unset($data);
/**
 *文件缓存方法
 *@param string $key 缓存文件键
 *@param mixed $value 缓存数据
 *@param integer $expire 过期时间(秒)
 *@return NULL | mixed
 */
function F($key,$value=NULL,$expire=NULL){
	$mc=memcache_init();
	if ($value==NULL) {
		$value=unserialize(memcache_get($mc,$key));
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
		memcache_set($mc,$key,$value);
		return;
	}
}
?>
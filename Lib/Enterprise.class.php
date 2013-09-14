<?php
/**
 * EnterPrise Framework
 * 框架运行静态类
 * @author Bokjan Chan
 * @version 0.1
 */
class Enterprise{
	static function run(){
		self::dispatcher();
	}
	static function dispatcher(){
		$path=$_SERVER['REQUEST_URI'];
		if(C('URL_SEPE')!=NULL){
			$seperator=C('URL_SEPE');
		}
		else{
			$seperator='/';
		}
		$path=str_replace($_SERVER['SCRIPT_NAME'].'/', '', $path);
		$path=str_replace(C('URL_SUFFIX'),'',$path);
		$path=explode($seperator, $path);
		$action=$path[0].'Action';
		$method=$path[1];
		unset($path[0],$path[1]);
		$key=array();
		$value=array();
		foreach ($path as $k => $v) {
			if ($k%2==0) {
				$k++;
				$_GET[$v]=$path[$k];
			}
		}
		/**
		 *$action 调用的控制器名
		 *$method 调用的对应方法名
		 *pathinfo中相关GET参数直接并入$_GET变量
		 *注：若?后的GET参数与pathinfo中有重复，则以pathinfo为准
		 */
		if (method_exists($action,$method)) {
			eval($action.'::'.$method.'();');
		} else {
			echo 'no';
			/*此处应执行异常处理，暂未完成异常处理方法*/
		}
	}
}
?>